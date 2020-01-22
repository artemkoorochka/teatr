var priceSwitcher = {

    selector: ".price-switcher",
    length: 240,
    segment: 80,
    speed: 300,
    state: {
        key: 0,
        href: null
    },
    flag: {
        selector: ".active-flag",
        state: 0,
    },

    a: function (t, key) {

        this.state.key = key;
        this.state.href = $(t).attr("href");
        this.flag.state = this.state.key * this.segment;

        $(this.flag.selector).animate({
            left: this.flag.state
        }, this.speed, function () {
            location.href = priceSwitcher.state.href;
        });

        return false;
    }

};