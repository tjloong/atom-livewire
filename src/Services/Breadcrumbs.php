<?php

namespace Jiannius\Atom\Services;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class Breadcrumbs
{
    /**
     * Set home
     */
    public function home($name, $url = null): void
    {
        $this->setTrailsToSession([
            'home' => array_merge($this->generateTrail($name, $url), ['is_home' => true]),
            'trails' => [],
            'fallback' => [],
        ]);
    }

    /**
     * Set home if condition matched
     */
    public function homeIf($bool, $name, $url = null): void
    {
        if ($bool) $this->home($name, $url);
    }

    /**
     * Set home if home is empty
     */
    public function homeIfEmpty($name, $url = null): void
    {
        if (!$this->get('home')) $this->home($name, $url);
    }

    /**
     * Push trails
     */
    public function push($name, $url = null)
    {
        $trails = collect($this->get('trails', []));
        $trail = $this->generateTrail($name, $url);
        $index = $trails->search(fn($val) => $val['route'] === $trail['route']);

        if ($index === false) $trails->push($trail);
        else $trails = $trails->reject(fn($val, $key) => $key > $index);

        $this->setTrailsToSession(array_merge($this->get(), [
            'trails' => $trails->values()->all(),
        ]));

        $this->updateReferer();

        return $this;
    }

    /**
     * Push if condition matched
     */
    public function pushIf($bool, $name, $url = null): void
    {
        if ($bool) $this->push($name, $url);
    }

    /**
     * Set fallback
     */
    public function fallback($trails)
    {
        $this->setTrailsToSession(array_merge($this->get(), ['fallback' => $trails]));

        return $this;
    }

    /**
     * Get trails
     * 
     * @param string $key
     * @return array
     */
    public function get($key = null, $default = null)
    {
        $session = session('breadcrumbs', []);

        if (!$key) return $session;

        return $session[$key] ?? $default;
    }

    /**
     * Replace current trail
     */
    public function replace($name)
    {
        $trails = $this->get('trails');
        array_pop($trails);

        $this->setTrailsToSession(array_merge($this->get(), [
            'trails' => $trails,
        ]));

        return $this->push($name);
    }

    /**
     * Get previous trail
     */
    public function previous()
    {
        $trails = $this->get('trails');

        if (!$trails) return null;
        else if (count($trails) <= 1) return $this->get('home');
        else return $trails[array_key_last($trails) - 1] ?? null;
    }

    /**
     * Flush trails
     */
    public function flush()
    {
        session()->forget('breadcrumbs');
    }

    /**
     * Generate trail object
     */
    private function generateTrail($name, $url = null): array
    {
        $url = $url ?? url()->current();

        return array_merge([
            'label' => __($name),
            'url' => $url,
            'route' => current_route(),
        ], parse_url($url));
    }

    /**
     * Set trails to session
     * 
     * @param array $trails
     * @return void
     */
    private function setTrailsToSession($trails)
    {
        session(['breadcrumbs' => $trails]);
    }

    /**
     * Update referer trail
     */
    private function updateReferer()
    {
        $previous = $this->previous();
        if (!$previous) return;

        $ref = url()->previous();
        $refroute = app(Router::class)->getRoutes()->match(
            app(Request::class)->create($ref)
        )->getName();

        if ($previous['route'] === $refroute && $previous['url'] !== $ref) {
            $data = array_merge($previous, ['url' => $ref], parse_url($ref));

            if ($data['is_home'] ?? false) {
                $this->setTrailsToSession(array_merge($this->get(), ['home' => $data]));
            }
            else {
                $trails = $this->get('trails');
                $trails[array_key_last($trails) - 1] = $data;
                $this->setTrailsToSession(array_merge($this->get(), ['trails' => $trails]));
            }
        }
    }

    /**
     * Back
     */
    public function back()
    {
        $sess = session('breadcrumbs');
        $home = data_get($sess, 'home');
        $trails = collect(data_get($sess, 'trails'));

        if (!$trails || $trails->count() <= 1) $dest = $home;
        else {
            $args = func_get_args();
            $dest = $args
                ? collect($args)->map(fn($arg) => $trails->firstWhere('url', $arg))->filter()->first()
                : null;

            if (!$dest) $dest = $trails->get($trails->count() - 2);
        }

        return $dest ? redirect(data_get($dest, 'url')) : redirect()->back();
    }
}