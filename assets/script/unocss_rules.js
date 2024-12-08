window.__unocss = {
    presets: [],
    rules: [
        [/^bg-color-(\w+)$/, ([, color]) => ({ 'background-color': color })],
        [/^m-(\d+)$/, ([, size]) => ({'margin': `${size}px` })],
        [/^m-(\w+)-(\d+)$/, ([, position, size]) => ({[`margin-${position}`]: `${size}px` })],
        [/^p-(\d+)$/, ([, size]) => ({'padding': `${size}px` })],
        [/^p-(\w+)-(\d+)$/, ([, position, size]) => ({[`padding-${position}`]: `${size}px` })],
        [/^text-(\d+)$/, ([, size]) => ({'font-size': `${size}px` })],
        [/^color-(\w+)$/, ([, color]) => ({'color': `${color}` })],
    ],
    shortcuts: [],
}