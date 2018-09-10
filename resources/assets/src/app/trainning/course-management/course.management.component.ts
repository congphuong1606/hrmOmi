import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {ActivatedRoute, NavigationEnd, Router} from '@angular/router';
import {DataGlobalService} from '../../services/data.global.service';
import {CourseService} from '../../services/course/course.service';
import {CoreOfUsers, CourseInListRepose, SessionQr} from '../../models/api/response/CourseRepose';
import {Subscription} from 'rxjs/Subscription';
import {link} from 'fs';

declare var $: any;
declare var swal: any;


@Component({
    selector: 'course-m-cmp',
    moduleId: module.id,
    templateUrl: 'course.management.component.html'
})

export class CourseManagementComponent implements OnInit, OnDestroy {
    curentCourse = '';
    dataRows: CourseInListRepose[] = [];
    isloadSuccess = false;
    arrayPage: number[] = [];
    isAdmin = false;
    allowUpdate = false;
    allowAdd = false;
    sessionQrs: SessionQr[] = [];
    coreOfUsers: CoreOfUsers[] = [];
    currentPage = 1;
    finished = '';
    lastPage = 1;
    perPage = 10;
    total = 0;
    textDefault = 'Tất cả khóa học';
    subscription: Subscription;
    sub: Subscription;
    searchData = '';

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
        if (!this.checkPemision('/dao-tao/quan-ly-khoa-hoc')) {
            window.history.back();
        } else {
            console.log(this.currentPage);
            this.allowUpdate = this.checkPemision('/dao-tao/cap-nhat-khoa-hoc');
            this.allowAdd = this.checkPemision('/dao-tao/tao-khoa-hoc');
            this.getDataRow();
            this.isAdmin = this.dataService.isAdmin();
        }

    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.currentPage = parseInt(queryParamMap.get('index_page') === null ? '1' : queryParamMap.get('index_page'));
        this.finished = queryParamMap.get('type') === null ? '' : queryParamMap.get('type');
    }


    checkPemision(path: string): boolean {
        return this.dataService.checkPemisson(path);
    }

    ngOnDestroy(): void {
        this.sub !== undefined ? this.sub.unsubscribe() : console.log('');
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log('');
    }

    isLoadSuccess(): boolean {
        if (this.isloadSuccess) {
            $('[rel="tooltip"]').tooltip();
        }
        return this.isloadSuccess;
    }

    getDataRow(): void {
        this.searchData = $('#search-input-request-course').val() === undefined ? '' : $('#search-input-request-course').val();
        this.dataRows.length = 0;
        this.sub = this.service.getList(this.currentPage, this.perPage, this.finished, this.searchData).subscribe(
            repo => {
                this.dataRows = (repo.courses as CourseInListRepose[]);
                this.currentPage = (repo.pagination.current_page as number);
                if (this.dataRows.length === 0 && this.currentPage >= 2) {
                    this.currentPage = this.currentPage - 1;
                    this.isloadSuccess = false;
                    console.log('AAAAAAAAA')
                    this.getDataRow();
                } else {
                    this.lastPage = (repo.pagination.last_page as number);
                    this.total = (repo.pagination.total as number);
                    this.arrayPage.length = 0;
                    for (let i = 1; i <= this.lastPage; i++) {
                        this.arrayPage.push(i);
                    }
                    this.isloadSuccess = true;
                }
                this.ngOnDestroy();
            },
            error => {
                this.ngOnDestroy();
                this.dataService.actionFail(error.message);
            });
    }


    showQrCode(row: any): void {
        this.curentCourse = row.course_name;
        this.sessionQrs.length = 0;
        let loadQr = false;
        const sub = this.service.getQrCode(row.id).subscribe(
            data => {
                this.sessionQrs = data.sessions as SessionQr[];
                loadQr = true;
                if (this.sessionQrs.length > 0) {
                    $('#qrOfCourse').modal('show');
                }
                sub.unsubscribe();
            },
            error => {
                loadQr = true;
                sub.unsubscribe();
            }
        );
    }

    showCore(idCourse: number): void {
        this.router.navigate(['../dao-tao/quan-ly-nhan-vien-trong-khoa-hoc'], {queryParams: {id_course: idCourse}});
    }

    getData(finished: string, title: string): void {
        this.textDefault = title;
        this.currentPage = 1;
        this.finished = finished;
        this.getDataRow();
    }

    print(idclass: string): void {
        let popupWin;
        const printContents = $('#' + idclass).html();
        let htmlQr = '<html>' +
            ' <head>' +
            '<style>img{cursor: pointer;width: 70%}</style> </head>' +
            '<body onload="window.print();window.close()">' +
            '<div style="text-align: center;margin-top: 100px" >' + printContents + '</div></body>' +
            '</html>';
        htmlQr = htmlQr.replace('In mã điểm danh này', '');
        htmlQr = htmlQr.replace('In mã điểm danh này', '');
        popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
        popupWin.document.open();
        popupWin.document.write(htmlQr);
        popupWin.document.close();
    }

    gotoUpdateCourse(id: any): void {
        this.router.navigate(['../dao-tao/cap-nhat-khoa-hoc'], {queryParams: {id_course: id}});
    }

    goToCreateCourse(): void {
        this.router.navigate(['../dao-tao/tao-khoa-hoc']);
    }

    onBack(): void {
        window.history.back();
    }

    deleteCourse(data: any): void {
        swal({
            title: 'Xác nhận xóa?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            this.service.delete(data.id).subscribe(
                repo => {
                    if (repo.status === 'success') {
                        swal('Đã Xóa!', 'Bạn đã xóa khóa học thành công', 'success');
                        this.dataRows = this.dataRows.filter(h => h !== data);
                        // if (this.dataRows.length === 0 && this.currentPage >= 2) {
                        //     this.currentPage = this.currentPage - 1;
                        //     this.isloadSuccess = false;
                        //     this.getDataRow();
                        // }
                    }
                },
                error => {
                    this.dataService.actionFail(error.error);
                });

        });
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
        this.router.navigate(['/dao-tao/quan-ly-khoa-hoc'],
            {
                queryParams: {
                    index_page: this.currentPage,
                    redirect_id: this.getRandomString(),
                    type: this.finished,
                }
            });
    }


    searchCourse() {
        this.getDataRow();
    }
}