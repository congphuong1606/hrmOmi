import {Component, OnInit, Renderer, ViewChild, ElementRef, Directive, OnDestroy} from '@angular/core';
import {ROUTES} from '../.././sidebar/sidebar.component';
import {Router, ActivatedRoute} from '@angular/router';
import {Location, LocationStrategy, PathLocationStrategy} from '@angular/common';
import {DataGlobalService} from '../../services/data.global.service';
import swal from 'sweetalert2';
import {Subscription} from 'rxjs/Subscription';

var misc: any = {
    navbar_menu_visible: 0,
    active_collapse: true,
    disabled_collapse_init: 0,
}
declare var $: any;

@Component({
    moduleId: module.id,
    selector: 'navbar-cmp',
    templateUrl: 'navbar.component.html'
})

export class NavbarComponent implements OnInit, OnDestroy {


    private listTitles: any[];
    private location: Location;
    private nativeElement: Node;
    private toggleButton;
    private sidebarVisible: boolean;
    private isAdmin = true;
    @ViewChild('navbar-cmp') button;
    subLogout: Subscription;
    isSidebarMini = false;

    constructor(location: Location,
                private renderer: Renderer,
                public dataGlobalService: DataGlobalService,
                private element: ElementRef, private router: Router) {
        this.location = location;
        this.nativeElement = element.nativeElement;
        this.sidebarVisible = false;
    }

    ngOnInit() {
        this.isAdmin = this.dataGlobalService.isAdmin();
        this.listTitles = ROUTES.filter(listTitle => listTitle);

        var navbar: HTMLElement = this.element.nativeElement;
        this.toggleButton = navbar.getElementsByClassName('navbar-toggle')[0];
        if ($('body').hasClass('sidebar-mini')) {
            misc.sidebar_mini_active = true;
        }
        $('#minimizeSidebar').click(a => {
            var $btn = $(this);

            if (misc.sidebar_mini_active == true) {
                $('body').removeClass('sidebar-mini');
                misc.sidebar_mini_active = false;
                this.isSidebarMini = false;

            } else {
                this.isSidebarMini = true;
                setTimeout(function () {
                    $('body').addClass('sidebar-mini');

                    misc.sidebar_mini_active = true;
                }, 300);
            }

            // we simulate the window Resize so the charts will get updated in realtime.
            var simulateWindowResize = setInterval(function () {
                window.dispatchEvent(new Event('resize'));
            }, 180);

            // we stop the simulation of Window Resize after the animations are completed
            setTimeout(function () {
                clearInterval(simulateWindowResize);
            }, 1000);
        });

        this.settingThemeSlidebar();
    }

    isMobileMenu() {
        if ($(window).width() < 991) {
            this.toggleButton.classList.remove('toggled');
            this.sidebarVisible = false;
            return false;
        }
        return true;
    }

    sidebarOpen() {
        var toggleButton = this.toggleButton;
        var body = document.getElementsByTagName('body')[0];
        setTimeout(function () {
            toggleButton.classList.add('toggled');
        }, 500);
        body.classList.add('nav-open');
        this.sidebarVisible = true;
    }

    sidebarClose() {
        var body = document.getElementsByTagName('body')[0];
        this.toggleButton.classList.remove('toggled');
        this.sidebarVisible = false;
        body.classList.remove('nav-open');
    }

    ngOnDestroy(): void {
        this.subLogout !== undefined ? this.subLogout.unsubscribe() : console.log('');
    }

    sidebarToggle() {
        // var toggleButton = this.toggleButton;
        // var body = document.getElementsByTagName('body')[0];
        if (this.sidebarVisible == false) {
            this.sidebarOpen();
            console.log('aaaaaaa');
        } else {
            console.log('bbbbbbbbbbb');
            this.sidebarClose();
        }
    }

    getTitle() {
        var titlee = this.location.prepareExternalUrl(this.location.path());
        if (titlee.charAt(0) === '#') {
            titlee = titlee.slice(2);
        }
        for (var item = 0; item < this.listTitles.length; item++) {
            var parent = this.listTitles[item];
            if (parent.path === titlee) {
                return parent.title;
            } else if (parent.children) {
                var children_from_url = titlee.split('/')[2];
                for (var current = 0; current < parent.children.length; current++) {
                    if (parent.children[current].path === children_from_url) {
                        return parent.children[current].title;
                    }
                }
            }
        }

        return 'Trang chủ';
    }

    getPath() {
        // console.log(this.location);
        return this.location.prepareExternalUrl(this.location.path());
    }

    logOut(): void {
        this.subLogout = this.dataGlobalService.logOut().subscribe(
            data => {
                localStorage.clear();
                this.ngOnDestroy();
                this.router.navigate(['../login']);
            }, error1 => {
                this.dataGlobalService.log(error1);
            }
        );
    }

    private settingThemeSlidebar() {
        var $sidebar = $('.sidebar');
        var $off_canvas_sidebar = $('.off-canvas-sidebar');
        var window_width = $(window).width();

        if (window_width > 767) {
            if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
                $('.fixed-plugin .dropdown').addClass('open');
            }

        }

        $('.fixed-plugin a').click(function (event) {
            if ($(this).hasClass('switch-trigger')) {
                if (event.stopPropagation) {
                    event.stopPropagation();
                } else if (window.event) {
                    window.event.cancelBubble = true;
                }
            }
        });

        $('.fixed-plugin .background-color span').click(function () {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');

            var new_color = $(this).data('color');
            console.log(new_color);

            if ($sidebar.length !== 0) {
                $sidebar.attr('data-background-color', new_color);
            } else {
                var $sa = $('.sidebar');
                $sa.attr('data-background-color', new_color);
                if ($sa.length != 0) {
                    $sa.attr('data-background-color', new_color);
                }
            }

            if ($off_canvas_sidebar.length !== 0) {
                $off_canvas_sidebar.attr('data-background-color', new_color);
            } else {
                var $s = $('.off-canvas-sidebar');
                $s.attr('data-background-color', new_color);
            }
        });

        $('.fixed-plugin .active-color span').click(function () {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');

            var new_color = $(this).data('color');

            if ($sidebar.length !== 0) {
                $sidebar.attr('data-active-color', new_color);
            } else {
                var $sa = $('.sidebar');
                console.log(new_color)
                $sa.attr('data-active-color', new_color);

            }

            if ($off_canvas_sidebar.length !== 0) {
                $off_canvas_sidebar.attr('data-active-color', new_color);
            } else {
                console.log(new_color)
                var $s = $('.off-canvas-sidebar');
                $s.attr('data-active-color', new_color);
            }
        });
    }

    goToSettingScreen() {
        this.router.navigate(['/cai-dat']);
    }

    goToUserInfor() {
        this.router.navigate(['/danh-sach-nhan-su/ho-so']);
    }
}
