import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {TimeOffService} from '../../../services/time-off/TimeOff.service';
import {Router, ActivatedRoute, NavigationEnd} from '@angular/router';
import {TimeOffApprover} from '../../../models/api/response/TimeOffReponse';
import {DataGlobalService} from '../../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';
import {OvertimeService} from '../../../services/overtime.service';
import {OvertimeApprover} from '../../../models/api/response/Overtime';

declare var $: any;
declare var swal: any;

declare interface Select {
    idSelect: number;
    isSelect: boolean;
}

declare interface Body {
    over_time_ids: number[];
    approved_reason: string;
}

// @Component({
//     selector: 'approve-overtime-cmp',
//     // moduleId: module.id,
//     templateUrl: 'approve-overtime.component.html'
// })


export class ApproveOvertimeComponent implements OnInit, OnDestroy {
    total = 0;
    isAll: boolean = false;
    perPage = 15;
    titleDropdownDefault = 'Đơn OT chờ duyệt';
    requestPram = '&&approved=0'
    arrayPage: number[] = [];
    lastPage = 1;
    dataRows: any[] = [];
    userId = -1;
    curentPage = 1;
    form: FormGroup;
    subscription: Subscription;
    subscriptionDataRows: Subscription;
    body: Body = {
        over_time_ids: [],
        approved_reason: '',
    };
    selects: Select[] = [];
    check = false;
    sub1: Subscription;
    otInfor: any;
    private type = '';

    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private authService: AuthService,
                private overtimeService: OvertimeService,
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
        if (!this.dataGlobalService.checkPemisson('/lam-them-gio-va-nghi-phep/duyet-lam-them-gio')) {
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
        this.curentPage = parseInt(queryParamMap.get('index_page') === null ? '1' : queryParamMap.get('index_page'));
        this.type = queryParamMap.get('type') === null ? 'Đơn OT chờ duyệt' : queryParamMap.get('type');
        switch (this.type) {
            case 'Tất cả đơn OT':
                this.requestPram = '&&approved=';
                break;
            case 'Đơn OT đã từ chối':
                this.requestPram = '&&approved=2';
                break;
            case 'Đơn OT đã đồng ý':
                this.requestPram = '&&approved=1';
                break;
            case 'Đơn OT chờ duyệt':
                this.requestPram = '&&approved=0';
                break;

        }
    }


    getUserinfo(): void {
        this.userId = this.dataGlobalService.getuserId();
    }

    getDataRows(): void {
        this.dataRows.length = 0;
        this.selects.length = 0;
        this.check = false;
        this.subscriptionDataRows = this.overtimeService.getListOverApprover(this.userId, this.curentPage, this.requestPram).subscribe(
            data => {
                this.dataRows = (data.over_times as any[]);
                this.lastPage = (data.pagination.last_page as number);
                this.total = (data.pagination.total as number);
                this.curentPage = (data.pagination.current_page as number);
                this.arrayPage.length = 0;
                for (let i = 1; i <= this.lastPage; i++) {
                    this.arrayPage.push(i);
                }
                this.dataRows.forEach(element => {
                    this.selects.push({idSelect: element.id, isSelect: false});
                });
                this.check = true;
                $('[rel="tooltip"]').tooltip();
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

    showInfor(idOt: number): void {
        this.sub1 = this.overtimeService.get(idOt).subscribe(
            data => {
                console.log(data);
                this.otInfor = data.over_time;
                this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
                if (this.otInfor !== undefined) {
                    $('#inforOT').modal('show');
                }
            },
            error1 => {
                this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
                this.dataGlobalService.actionFail(error1.error);
            }
        );

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
                    this.body.over_time_ids.push(element.idSelect);
                }
            });
        } else {
            this.body.over_time_ids.length = 0;
            this.body.over_time_ids.push(this.otInfor.id);
        }

        this.showSweet();


    }

    showSweet(): void {
        swal({
            title: 'Từ chối',
            text: 'Lưu ý: Bạn sẽ thông báo tới nhân sự này và yêu cầu tạo lại đơn xin làm ngoài giờ',
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
                this.overtimeService.refuseOvertime(this.body).subscribe(
                    repo => {
                        swal('Thành công', 'Bạn đã từ chối đơn làm ngoài giờ', 'success');
                        this.refreshPage();
                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                    });
            } else {
                swal('Thông tin thiếu', 'Chưa nhập thông báo khi bạn không đồng ý!', 'error').then(r => {
                    this.showSweet();
                });
            }
        });
    }

    approve(type: number): void {

        if (type === 0) {
            this.selects.forEach(element => {
                if (element.isSelect) {
                    this.body.over_time_ids.push(element.idSelect);
                }
            });
        } else {
            this.body.over_time_ids.length = 0;
            this.body.over_time_ids.push(this.otInfor.id);
        }
        swal({
            title: 'Đồng ý đơn làm ngoài giờ?',
            text: 'Các đơn được chọn sẽ được duyệt sau khi bạn đồng ý',
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
            this.overtimeService.approveOvertime(this.body).subscribe(
                repo => {
                    swal('Thành công', 'Đã duyệt đơn làm ngoài giờ', 'success').then(r => {
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
        this.curentPage = index;
        this.router.navigate(['/lam-them-gio-va-nghi-phep/duyet-lam-them-gio'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    redirect_id: this.getRandomString()
                }
            });
    }

    refreshPage(): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/duyet-lam-them-gio'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    redirect_id: this.getRandomString(),
                    type: this.titleDropdownDefault,
                }
            });
        this.isAll = false;
        this.selects.forEach(element => {
            if (element.isSelect) {
                element.isSelect = false;
                this.dataRows = this.dataRows.filter(h => h.id !== element.idSelect);
            }
        });
        this.body.over_time_ids.length = 0;
    }

    refreshData(request: string, title: string) {
        this.curentPage = 1;
        this.titleDropdownDefault = title;
        this.requestPram = request;
        this.getDataRows();

    }
}

