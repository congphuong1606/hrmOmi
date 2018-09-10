import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/authSevice';
import {TimeOffService} from '../../services/time-off/TimeOff.service';
import {Router, ActivatedRoute, NavigationEnd} from '@angular/router';
import {TimeOffApprover} from '../../models/api/response/TimeOffReponse';
import {DataGlobalService} from '../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';

declare var $: any;
declare var swal: any;

declare interface Select {
    idSelect: number;
    isSelect: boolean;
}

declare interface Body {
    time_off_ids: number[];
    approved_reason: string;
}

@Component({
    selector: 'approve-list-cmp',
    moduleId: module.id,
    templateUrl: 'approve-list.component.html'
})


export class ApproveListComponent implements OnInit, OnDestroy {
    totalTimeOff = 0;
    perPage = 15;
    check = false;
    isAll: boolean = false;
    titleDropdownDefault = 'Đơn xin phép chờ duyệt';
    requestPram = '&&approved=0'
    arrayPage: number[] = [];
    lastPage = 1;
    dataRows: TimeOffApprover[] = [];
    userId = -1;
    currentPage = 1;
    form: FormGroup;
    subscription: Subscription;
    subscriptionDataRows: Subscription;
    body: Body = {
        time_off_ids: [],
        approved_reason: '',
    };
    selects: Select[] = [];
    type = '';
    timOffInfor: TimeOffApprover;
    showId = '';

    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private authService: AuthService,
                private timeOffService: TimeOffService,
                public dataGlobalService: DataGlobalService,
                private route: ActivatedRoute,
                private router: Router) {

    }

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
        this.subscriptionDataRows !== undefined ? this.subscriptionDataRows.unsubscribe() : console.log(':D');
    }

    ngOnInit() {
        this.subscription = this.router.events.subscribe(val => {
            if (val instanceof NavigationEnd) {
                this.getQueryParamRouter();
                if (this.userId !== -1) {
                    this.getDataRows();
                }
            }
        });
        this.getQueryParamRouter();
        if (!this.dataGlobalService.checkPemisson('/lam-them-gio-va-nghi-phep/duyet-nghi-phep')) {
            window.history.back();
        } else {
            if (this.userId === -1) {
                this.getUserinfo();
                this.getDataRows();
            }
        }

    }

    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }


    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.currentPage = parseInt(queryParamMap.get('index_page') === null ? '1' : queryParamMap.get('index_page'));
        this.type = queryParamMap.get('type') === null ? 'Đơn xin phép chờ duyệt' : queryParamMap.get('type');
        this.showId = queryParamMap.get('show_id') === null ? '' : queryParamMap.get('show_id');
        switch (this.type) {
            case 'Tất cả đơn xin phép':
                this.requestPram = '&&approved=';
                break;
            case 'Đơn xin phép đã từ chối':
                this.requestPram = '&&approved=2';
                break;
            case 'Đơn xin phép đã đồng ý':
                this.requestPram = '&&approved=1';
                break;
            case 'Đơn xin phép chờ duyệt':
                this.requestPram = '&&approved=0';
                break;

        }
    }


    getUserinfo(): void {
        this.userId = this.dataGlobalService.getuserId();
    }

    getDataRows(): void {
        this.dataRows.length = 0;
        this.check = false;
        this.selects.length = 0;
        this.subscriptionDataRows = this.timeOffService.getListTimeOffApprover(this.userId, this.currentPage, this.requestPram).subscribe(
            data => {
                this.dataRows = (data.times_off as TimeOffApprover[]);
                this.lastPage = (data.pagination.last_page as number);
                this.totalTimeOff = (data.pagination.total as number);
                this.currentPage = (data.pagination.current_page as number);
                this.arrayPage.length = 0;
                for (let i = 1; i <= this.lastPage; i++) {
                    this.arrayPage.push(i);
                }
                this.check = true;
             //   let flag = false;
                this.dataRows.forEach(element => {
                    this.selects.push({idSelect: element.id, isSelect: false});
                   /* if (element.id + '' === this.showId) {
                        this.showInforTimeOff(element);
                        flag = true;
                    }*/
                });
               /* if (this.showId !== '' && !flag) {
                    alert('Đơn đã bị xóa hoặc đã được duyệt trước đó!');
                }*/


            },
            error => {
                this.check = true;
                this.dataGlobalService.actionFail(error.message);
            }
        );

    }


    // kiểm tra xem có chọn đơn đăng ký nào không (checkbox)
    checkSelect(): boolean {
        let flag = false;
        this.selects.forEach(element => {
            if (element.isSelect) {
                flag = true;
            }
        });
        return flag;
    }

    selectItem(value: number): void {
        this.selects.forEach(element => {
            if (element.idSelect === value) {
                element.isSelect = !element.isSelect;
            }
        });
    }


    selectAllItem(): void {
        this.isAll = !this.isAll;
        if (this.isAll) {
            this.selects.forEach(element => {
                element.isSelect = true;
            });
        } else {
            this.selects.forEach(element => {
                element.isSelect = false;
            });
        }
        console.log(this.selects);
    }

    checkStatusSelect(value: number): boolean {
        let flag = false;
        this.selects.forEach(element => {
            if (element.idSelect === value) {
                flag = element.isSelect;
            }
        });
        return flag;
    }


    isLoadSuccess(): boolean {
        if (this.check) {
            $('[rel="tooltip"]').tooltip();
        }
        return this.check;
    }

    refuseTimeOff(type: number): void {
        if (type === 0) {
            this.selects.forEach(element => {
                if (element.isSelect) {
                    this.body.time_off_ids.push(element.idSelect);
                }
            });
        } else {
            this.body.time_off_ids.length = 0;
            this.body.time_off_ids.push(this.timOffInfor.id);
        }
        this.showSweet();


    }

    showSweet(): void {
        swal({
            title: 'Từ chối',
            text: 'Lưu ý: Bạn sẽ thông báo tới nhân sự này và yêu cầu tạo lại đơn xin phép',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Từ chối',
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
            console.log(result);
            if (result !== '') {
                this.body.approved_reason = result;
                this.timeOffService.refuseTimeOff(this.body).subscribe(
                    repo => {
                        swal('Thành công', 'Bạn đã từ chối đơn nghỉ phép', 'success').then(r => {
                        });
                        this.refreshPage();

                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                    });
            } else {
                swal('Thông tin thiếu', 'Chưa nhập thông báo khi bạn không đồng ý!', 'error').then(
                    r => {
                        this.showSweet();
                    });
            }
        });
    }

    approve(type: number): void {
        if (type === 0) {
            this.selects.forEach(element => {
                if (element.isSelect) {
                    this.body.time_off_ids.push(element.idSelect);
                }
            });
        } else {
            this.body.time_off_ids.length = 0;
            this.body.time_off_ids.push(this.timOffInfor.id);
        }
        swal({
            title: 'Đồng ý đơn xin phép?',
            text: type === 0 ? 'Các đơn được chọn sẽ được duyệt sau khi bạn đồng ý' : 'Đơn sẽ được duyệt sau khi bạn đồng ý',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },

        }).then(result => {
            this.timeOffService.approveTimeOff(this.body).subscribe(
                repo => {
                    swal('Thành công', 'Đã duyệt đơn nghỉ phép', 'success').then(r => {
                    });
                    this.refreshPage();
                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
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
        this.router.navigate(['/lam-them-gio-va-nghi-phep/duyet-nghi-phep'],
            {
                queryParams: {
                    index_page: this.currentPage,
                    redirect_id: this.getRandomString(),
                    type: this.titleDropdownDefault,
                }
            });
    }

    refreshPage(): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/duyet-nghi-phep'],
            {
                queryParams: {
                    index_page: this.currentPage,
                    redirect_id: this.getRandomString()
                }
            });
        this.isAll = false;
        this.selects.forEach(element => {
            if (element.isSelect) {
                element.isSelect = false;
                this.dataRows = this.dataRows.filter(h => h.id !== element.idSelect);
            }

        });

        this.body.time_off_ids.length = 0;
    }

    refreshData(request: string, title: string) {
        this.titleDropdownDefault = title;
        this.currentPage = 1;
        this.requestPram = request;
        this.getDataRows();

    }


    showInforTimeOff(row: TimeOffApprover) {
        this.timOffInfor = row;
        if (this.timOffInfor !== undefined) {
            $('#inforTimeOff').modal('show');
        }
    }
}

