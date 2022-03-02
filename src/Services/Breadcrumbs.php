<?php

namespace Jiannius\Atom\Services;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class Breadcrumbs
{
    /**
     * Set home
     * 
     * @param string $name
     * @return $this
     */
    public function home($name)
    {
        $this->setTrailsToSession([
            'home' => array_merge($this->generateTrail($name), ['is_home' => true]),
            'trails' => [],
            'fallback' => [],
        ]);
    }

    /**
     * Push trails
     */
    public function push($name)
    {
        $trails = collect($this->get('trails', []));
        $trail = $this->generateTrail($name);
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
     * Get previous trail
     */
    public function previous()
    {
        $trails = $this->get('trails');

        if (count($trails) <= 1) return $this->get('home');
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
     * 
     * @param string $name
     * @return array
     */
    private function generateTrail($name)
    {
        $url = url()->current();

        return array_merge([
            'label' => $name,
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
}