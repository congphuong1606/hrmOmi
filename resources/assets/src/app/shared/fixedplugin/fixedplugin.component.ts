import {Component, OnInit} from '@angular/core';


declare var $: any;

@Component({
    moduleId: module.id,
    selector: 'fixedplugin-cmp',
    templateUrl: 'fixedplugin.component.html'
})

export class FixedPluginComponent implements OnInit {
    ngOnInit() {
        var $sidebar = $('.sidebar');
        var $off_canvas_sidebar = $('.off-canvas-sidebar');
        var window_width = $(window).width();

        if (window_width > 767) {
            if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
                $('.fixed-plugin .dropdown').addClass('open');
            }

        }

        $('.fixed-plugin a').click(function (event) {
            // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
            if ($(this).hasClass('switch-trigger')) {
                if (event.stopPropagation) {
                    event.stopPropagation();
                }
                else if (window.event) {
                    window.event.cancelBubble = true;
                }
            }
        });

        $('.fixed-plugin .background-color span').click(function () {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');

            var new_color = $(this).data('color');
            console.log(new_color);

            if ($sidebar.length != 0) {
                $sidebar.attr('data-background-color', new_color);
            }else{
                var $sa = $('.sidebar');
                $sa.attr('data-background-color', new_color);
                if ($sa.length != 0) {
                    $sa.attr('data-background-color', new_color);
                }
            }

            if ($off_canvas_sidebar.length != 0) {
                $off_canvas_sidebar.attr('data-background-color', new_color);
            }else {
                var $s = $('.off-canvas-sidebar');
                $s.attr('data-background-color', new_color);
            }
        });

        $('.fixed-plugin .active-color span').click(function () {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');

            var new_color = $(this).data('color');

            if ($sidebar.length != 0) {
                $sidebar.attr('data-active-color', new_color);
            }else{
                var $sa = $('.sidebar');
                console.log(new_color)
                    $sa.attr('data-active-color', new_color);

            }

            if ($off_canvas_sidebar.length != 0) {
                $off_canvas_sidebar.attr('data-active-color', new_color);
            }else {
                console.log(new_color)
                var $s = $('.off-canvas-sidebar');
                $s.attr('data-active-color', new_color);
            }
        });
    }
}
