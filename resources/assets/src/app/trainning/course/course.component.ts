import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {ActivatedRoute, NavigationEnd, Router} from '@angular/router';
import {DataGlobalService} from '../../services/data.global.service';
import {CourseService} from '../../services/course/course.service';
import {CourseInListRepose, OneCourseForUser} from '../../models/api/response/CourseRepose';
import {Subscription} from 'rxjs/Subscription';

declare var $: any;
declare var swal: any;


@Component({
    selector: 'course-cmp',
    moduleId: module.id,
    templateUrl: 'course.component.html'
})

export class CourseComponent implements OnInit, OnDestroy {
    dataRows: OneCourseForUser[] = [];
    isloadSuccess = false;
    arrayPage: number[] = [];
    form: FormGroup;
    allowUpdate = false;
    allowAdd = false;
    currentPage = 1;
    morePramsRequest = '&&own=1';
    lastPage = 1;
    perPage = 15;
    total = 0;
    curentCourseSelected: OneCourseForUser = new OneCourseForUser();
    textDefault = 'Tất cả khóa học của tôi';
    sub: Subscription;
    type = '';
    subscription;

    constructor(private router: Router,
                private service: CourseService,
                private route: ActivatedRoute,
                public dataService: DataGlobalService) {

    }





    ngOnInit() {
        this.subscription = this.router.events.subscribe(val => {
            if (val instanceof NavigationEnd) {
                this.getQueryParamRouter();
                this.getDataRow();
            }
        });
        this.getQueryParamRouter();
        this.getDataRow();
    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.currentPage = parseInt(queryParamMap.get('index_page') === null ? '1' : queryParamMap.get('index_page'));
        this.type = queryParamMap.get('type') === null ? 'Tất cả khóa học của tôi' : queryParamMap.get('type');
        if (this.type !== 'Tất cả khóa học của tôi') {
            this.type === 'Chưa hoàn thành' ? this.morePramsRequest = '&&own=1&&completed=0'
                : this.morePramsRequest = '&&own=1&&completed=1';
        } else {
            this.morePramsRequest = '&&own=1';
        }
    }


    checkPemision(path: string): boolean {
        return this.dataService.checkPemisson(path);
    }


    isLoadSuccess(): boolean {
        if (this.isloadSuccess) {
            $('[rel="tooltip"]').tooltip();
        }
        return this.isloadSuccess;
    }

    ngOnDestroy(): void {
        this.sub !== undefined ? this.sub.unsubscribe() : console.log('');
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log('');
    }

    getDataRow(): void {
        this.dataRows.length = 0;
        this.sub = this.service.getListCourseForUser(this.currentPage, this.perPage, this.morePramsRequest).subscribe(
            data => {
                console.log(data);
                this.dataRows = (data.courses as OneCourseForUser[]);
                this.isloadSuccess = true;
                if (data.pagination !== undefined) {
                    this.currentPage = (data.pagination.current_page as number);
                    this.lastPage = (data.pagination.last_page as number);
                    this.total = (data.pagination.total as number);
                }
                this.arrayPage.length = 0;
                for (let i = 1; i <= this.lastPage; i++) {
                    this.arrayPage.push(i);
                }
                this.isloadSuccess = true;
            },
            error => {
                this.dataService.actionFail(error.message);
            });
    }

    showModalSession(row: OneCourseForUser): void {
        this.curentCourseSelected = row;
        if (this.curentCourseSelected !== {}) {
            $('#sessionList').modal('show');
        }
    }

    getData(morePramsRequest: string, title: string): void {
        this.textDefault = title;
        this.currentPage = 1;
        this.morePramsRequest = morePramsRequest;
        this.getDataRow();
    }


    getRandomString() {
        let text = '';
        const possible = 'abcdefghijklmnopqrstuvwxyz0123456789';
        for (let i = 0; i < 5; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    }

    onChangPage(index: number): void {
        this.currentPage = index;
        this.refreshPage();
    }

    refreshPage(): void {
        this.router.navigate(['/dao-tao/danh-sach-khoa-hoc'],
            {
                queryParams: {
                    index_page: this.currentPage,
                    redirect_id: this.getRandomString(),
                    type: this.textDefault,
                }
            })
        ;
    }


}

