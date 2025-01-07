import Ajax from './ajax.js'

export default {
    ajax: (url) => (new Ajax(url)),
    json: (...args) => (JSON.stringify(...args)),
    random: () => Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15),
    dispatch: (name, detail) => dispatchEvent(new CustomEvent(name, { bubbles: true, detail })),
    lightbox: (detail) => dispatchEvent(new CustomEvent('lightbox', { bubbles: true, detail })),
    action: (name, payload) => (new Ajax('/__action/'+name).post(payload)),

    goto: (url = null, newtab = false) => {
        if (!url) return
        if (newtab) Atom.newtab(url)
        else window.location = url
    },

    newtab: (url = null) => {
        if (!url) return
        window.open(url, '_blank')
    },

    // get highest zindex
    highestZIndex: () => {
        let z = []

        for (let dom of document.querySelectorAll('body *:not(script, style)')) {
            let zvalue = window.getComputedStyle(dom, null).getPropertyValue('z-index')
            let display = window.getComputedStyle(dom, null).getPropertyValue('display')

            if (zvalue !== null && zvalue !== 'auto' && zvalue < 999 && display !== 'none') {
                z.push(+zvalue)
            }
        }

        if (!z.length) return 0

        return Math.max(...z)
    },

    // check for emptyness
    empty: (value) => {
        if (value === undefined || value === null) return true

        value = JSON.parse(JSON.stringify(value))

        return (Array.isArray(value) && !value.length)
            || (typeof value === 'object' && !Object.keys(value).length && Object.getPrototypeOf(value) === Object.prototype)
            || (typeof value === 'string' && value.trim() === '')
    },

    // check page is reloaded
    isPageReloaded: () => {
        return (window.performance.navigation && window.performance.navigation.type === 1)
            || window.performance.getEntriesByType('navigation').map((nav) => nav.type).includes('reload')
    },

    // invert color
    invertColor: (hex, perc = 0.8) => {
        // https://stackoverflow.com/questions/5560248/programmatically-lighten-or-darken-a-hex-color-or-rgb-and-blend-colors
        const pSBC=(p,c0,c1,l)=>{
            let r,g,b,P,f,t,h,m=Math.round,a=typeof(c1)=="string";
            if(typeof(p)!="number"||p<-1||p>1||typeof(c0)!="string"||(c0[0]!='r'&&c0[0]!='#')||(c1&&!a))return null;
            h=c0.length>9,h=a?c1.length>9?true:c1=="c"?!h:false:h,f=pSBC.pSBCr(c0),P=p<0,t=c1&&c1!="c"?pSBC.pSBCr(c1):P?{r:0,g:0,b:0,a:-1}:{r:255,g:255,b:255,a:-1},p=P?p*-1:p,P=1-p;
            if(!f||!t)return null;
            if(l)r=m(P*f.r+p*t.r),g=m(P*f.g+p*t.g),b=m(P*f.b+p*t.b);
            else r=m((P*f.r**2+p*t.r**2)**0.5),g=m((P*f.g**2+p*t.g**2)**0.5),b=m((P*f.b**2+p*t.b**2)**0.5);
            a=f.a,t=t.a,f=a>=0||t>=0,a=f?a<0?t:t<0?a:a*P+t*p:0;
            if(h)return"rgb"+(f?"a(":"(")+r+","+g+","+b+(f?","+m(a*1000)/1000:"")+")";
            else return"#"+(4294967296+r*16777216+g*65536+b*256+(f?m(a*255):0)).toString(16).slice(1,f?undefined:-2)
        }

        pSBC.pSBCr=(d)=>{
            const i=parseInt;
            let n=d.length,x={};
            if(n>9){
                const [r, g, b, a] = (d = d.split(','));
                    n = d.length;
                if(n<3||n>4)return null;
                x.r=i(r[3]=="a"?r.slice(5):r.slice(4)),x.g=i(g),x.b=i(b),x.a=a?parseFloat(a):-1
            }else{
                if(n==8||n==6||n<4)return null;
                if(n<6)d="#"+d[1]+d[1]+d[2]+d[2]+d[3]+d[3]+(n>4?d[4]+d[4]:"");
                d=i(d.slice(1),16);
                if(n==9||n==5)x.r=d>>24&255,x.g=d>>16&255,x.b=d>>8&255,x.a=Math.round((d&255)/0.255)/1000;
                else x.r=d>>16,x.g=d>>8&255,x.b=d&255,x.a=-1
            }return x
        }

        let colors = {
            "aliceblue":"#f0f8ff","antiquewhite":"#faebd7","aqua":"#00ffff","aquamarine":"#7fffd4","azure":"#f0ffff",
            "beige":"#f5f5dc","bisque":"#ffe4c4","black":"#000000","blanchedalmond":"#ffebcd","blue":"#0000ff","blueviolet":"#8a2be2","brown":"#a52a2a","burlywood":"#deb887",
            "cadetblue":"#5f9ea0","chartreuse":"#7fff00","chocolate":"#d2691e","coral":"#ff7f50","cornflowerblue":"#6495ed","cornsilk":"#fff8dc","crimson":"#dc143c","cyan":"#00ffff",
            "darkblue":"#00008b","darkcyan":"#008b8b","darkgoldenrod":"#b8860b","darkgray":"#a9a9a9","darkgreen":"#006400","darkkhaki":"#bdb76b","darkmagenta":"#8b008b","darkolivegreen":"#556b2f",
            "darkorange":"#ff8c00","darkorchid":"#9932cc","darkred":"#8b0000","darksalmon":"#e9967a","darkseagreen":"#8fbc8f","darkslateblue":"#483d8b","darkslategray":"#2f4f4f","darkturquoise":"#00ced1",
            "darkviolet":"#9400d3","deeppink":"#ff1493","deepskyblue":"#00bfff","dimgray":"#696969","dodgerblue":"#1e90ff",
            "firebrick":"#b22222","floralwhite":"#fffaf0","forestgreen":"#228b22","fuchsia":"#ff00ff",
            "gainsboro":"#dcdcdc","ghostwhite":"#f8f8ff","gold":"#ffd700","goldenrod":"#daa520","gray":"#808080","green":"#008000","greenyellow":"#adff2f",
            "honeydew":"#f0fff0","hotpink":"#ff69b4",
            "indianred ":"#cd5c5c","indigo":"#4b0082","ivory":"#fffff0","khaki":"#f0e68c",
            "lavender":"#e6e6fa","lavenderblush":"#fff0f5","lawngreen":"#7cfc00","lemonchiffon":"#fffacd","lightblue":"#add8e6","lightcoral":"#f08080","lightcyan":"#e0ffff","lightgoldenrodyellow":"#fafad2",
            "lightgrey":"#d3d3d3","lightgreen":"#90ee90","lightpink":"#ffb6c1","lightsalmon":"#ffa07a","lightseagreen":"#20b2aa","lightskyblue":"#87cefa","lightslategray":"#778899","lightsteelblue":"#b0c4de",
            "lightyellow":"#ffffe0","lime":"#00ff00","limegreen":"#32cd32","linen":"#faf0e6",
            "magenta":"#ff00ff","maroon":"#800000","mediumaquamarine":"#66cdaa","mediumblue":"#0000cd","mediumorchid":"#ba55d3","mediumpurple":"#9370d8","mediumseagreen":"#3cb371","mediumslateblue":"#7b68ee",
            "mediumspringgreen":"#00fa9a","mediumturquoise":"#48d1cc","mediumvioletred":"#c71585","midnightblue":"#191970","mintcream":"#f5fffa","mistyrose":"#ffe4e1","moccasin":"#ffe4b5",
            "navajowhite":"#ffdead","navy":"#000080",
            "oldlace":"#fdf5e6","olive":"#808000","olivedrab":"#6b8e23","orange":"#ffa500","orangered":"#ff4500","orchid":"#da70d6",
            "palegoldenrod":"#eee8aa","palegreen":"#98fb98","paleturquoise":"#afeeee","palevioletred":"#d87093","papayawhip":"#ffefd5","peachpuff":"#ffdab9","peru":"#cd853f","pink":"#ffc0cb","plum":"#dda0dd","powderblue":"#b0e0e6","purple":"#800080",
            "rebeccapurple":"#663399","red":"#ff0000","rosybrown":"#bc8f8f","royalblue":"#4169e1",
            "saddlebrown":"#8b4513","salmon":"#fa8072","sandybrown":"#f4a460","seagreen":"#2e8b57","seashell":"#fff5ee","sienna":"#a0522d","silver":"#c0c0c0","skyblue":"#87ceeb","slateblue":"#6a5acd","slategray":"#708090","snow":"#fffafa","springgreen":"#00ff7f","steelblue":"#4682b4",
            "tan":"#d2b48c","teal":"#008080","thistle":"#d8bfd8","tomato":"#ff6347","turquoise":"#40e0d0",
            "violet":"#ee82ee",
            "wheat":"#f5deb3","white":"#ffffff","whitesmoke":"#f5f5f5",
            "yellow":"#ffff00","yellowgreen":"#9acd32",
        }

        hex = colors[hex] || hex

        return pSBC(perc, hex)
    },

    // screen size
    screensize: (size = null) => {
        const w = window.innerWidth
        let value

        if (w <= 640) value = 'sm'
        if (w > 640 && w <= 768) value = 'md'
        if (w > 768 && w <= 1024) value = 'lg'
        if (w > 1024 && w <= 1280) value = 'xl'
        if (w > 1280 && w <= 1536) value = '2xl'
        if (w > 1536 && w <= 2000) value = '3xl'

        if (size) return [size].flat().includes(value)
        else return value
    },

    // device type
    deviceType: () => {
        const ua = navigator.userAgent

        if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
            return 'tablet'
        }
        else if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
            return 'mobile'
        }

        return 'desktop'
    },

    // get
    get: (haystack, needle) => {
        let value = { ...haystack }
        let keys = needle.split('.')

        while(keys.length && value) {
            let key = keys.shift()
            value = value.hasOwnProperty(key) ? value[key] : null
        }

        return value
    },

    // translate
    tr: (...args) => {
        if (!window.lang) return args[0]

        let key = args.shift()
        let lang = Atom.get(window.lang, key)

        if (!lang && !key.startsWith('app.')) lang = Atom.get(window.lang, `app.label.${key}`)
        if (!lang) return key
        if (typeof lang !== 'string') return key

        let arg = args[0]
        let split = lang.split('|')
        let singular = split[0]
        let plural = split[1]

        if (Atom.empty(arg)) {
            return singular
        }

        if (typeof arg === 'number') {
            if (arg > 1 && plural) return plural.replace(':count', arg)
            else return singular.replace(':count', arg)
        }

        if (typeof arg === 'object') {
            Object.keys(arg).forEach(key => {
                singular = singular.replace(`:${key}`, arg[key])
            })

            return singular
        }
    },

    // short for tr()
    t: (...args) => (Atom.tr(...args)),
}