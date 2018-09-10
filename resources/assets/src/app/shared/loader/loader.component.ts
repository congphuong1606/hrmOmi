import { Component, OnInit, Renderer, ViewChild, ElementRef, Directive } from '@angular/core';
import { ROUTES } from '../.././sidebar/sidebar.component';
import { Router, ActivatedRoute } from '@angular/router';
import { Location, LocationStrategy, PathLocationStrategy } from '@angular/common';

declare var $: any;

@Component({
    moduleId: module.id,
    selector: 'loader-cmp',
    templateUrl: 'loader.component.html',
    styleUrls: ['./loader.component.css']
})

export class LoaderComponent implements OnInit {

    constructor(location: Location, private renderer: Renderer, private element: ElementRef) {
    }

    ngOnInit() {

    }

}
