declare var $: any;
export class LoaderController {
    counter: number;
    enable: boolean;
    selector = '#material-loader';

    constructor() {
        this.counter = 0;
        this.enable = true;
    }

    setSelector(selector) {
        this.selector = selector;
    }

    pushLoader() {
        this.counter++;
        this.displayLoader();
    }

    releaseLoader() {
        this.counter--;
        this.displayLoader();
    }

    cancelLoader() {
        this.enable = false;
        this.displayLoader();
    }

    enableLoader() {
        this.enable = true;
        this.displayLoader();
    }

    displayLoader() {
        if (this.enable && this.counter) {
            $(this.selector).css({
                display: 'flex'
            });
        } else {
            $(this.selector).css({
                display: 'none'
            });
        }
    }
}