import {Component, OnInit, OnDestroy} from '@angular/core';
import {Router} from '@angular/router';

declare var $: any;
declare var swal: any;


@Component({
    selector: 'jobstatus-cmp',
    moduleId: module.id,
    templateUrl: 'demo.html'
})

export class DemoComponent implements OnInit, OnDestroy {
    arrNumber: any[] = [];

    ngOnDestroy(): void {

    }

    constructor(private router: Router) {
    }

    ngOnInit() {
        for (let i = 0; i < 3000; i++) {
            const obj = {
                'id': i,
                'name': 'Name' + i,
                'province' : 'Province ' + i,
                'province_name' : 'Name Province' + i
            };
            this.arrNumber.push(obj);
        }
    }


}
