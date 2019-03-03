var ScrollbarCustom = window.Scrollbar;
ScrollbarCustom.use(window.OverscrollPlugin);

ScrollbarCustom = ScrollbarCustom.init($('.body')[0], {
    damping: 0.12,
    thumbMinSize: 20,
    renderByPixels: true,
    alwaysShowTracks: false,
    continuousScrolling: true,
    plugins: {
        overscroll: {
            effect: 'bounce',
            damping: 0.2,
            maxOverscroll: 150
        }
    }
});