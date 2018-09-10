import {Component, OnInit, OnDestroy, Input, OnChanges, SimpleChanges} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../services/authSevice';
import {HomeService} from '../services/home.service';
import {DataGlobalService} from '../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';
import {FcmserviceService} from '../services/fcm/fcmservice.service';
import * as constants from '../constants';
import {Router} from '@angular/router';
import {TimeOffService} from '../services/time-off/TimeOff.service';
// import {FcmService} from '../services/FcmSevice';

declare var $: any;
declare var swal: any;

declare interface Body {
    time_off_ids: number[];
    approved_reason: string;
}

@Component({
    selector: 'home-cmp',
    moduleId: module.id,
    templateUrl: 'home.component.html',
})


export class HomeComponent implements OnInit, OnDestroy {
    form: FormGroup;
    sub: Subscription;
    sub1: Subscription;
    check1 = false;
    check2 = false;
    notifications: any[] = [];
    employeeBirthDays: any[] = [];
    message;
    news: any;
    inforTimeOff: any;
    homepageInfo: any;
    noticePage = 1;
    lastPage = 1;
    updateReadIsLoading = false;

    isAdvancedAdmin = false;
    sub2: Subscription;

    body: Body = {
        time_off_ids: [],
        approved_reason: '',
    };

    constructor(public fb: FormBuilder,
                public http: HttpClient,
                public homeSevice: HomeService,
                public router: Router,
                public fcmService: FcmserviceService,
                public dataGlobalService: DataGlobalService,
                public timeOffService: TimeOffService,
                public authService: AuthService) {
        this.fcmService.currentMessage.subscribe(
            value => {
                this.getListNotification();
            }
        );

    }

    ngOnInit() {
        this.rememberLogin();
        this.dataGlobalService.setLocation(true);
        this.fcmService.getPermission();
        this.fcmService.receiveMessage();
        this.message = this.fcmService.currentMessage;
        this.getData();
        this.isAdvancedAdmin = this.dataGlobalService.isAdvancedAdmin();
        if (!this.isAdvancedAdmin) {
            this.getHomepageInfo();
        }
    }

    ngOnDestroy(): void {
        this.dataGlobalService.setLocation(false);
        this.sub.unsubscribe() !== undefined ? this.sub.unsubscribe() : console.log('');
        this.sub1.unsubscribe() !== undefined ? this.sub1.unsubscribe() : console.log('');
    }

    getHomepageInfo() {
        this.homeSevice.getHomepageInfo().subscribe(
            (data) => {
                this.homepageInfo = data.info;
            },
            (error) => {
                console.log(error);
            }
        );
    }

    private getData() {
        this.getListNotification();
        this.getListBirthdayInWeek();
    }

    private getListNotification() {
        this.sub = this.homeSevice.getNotifications(this.noticePage).subscribe(
            data => {
                if (this.noticePage === 1) {
                    this.notifications = data.notifications as any[];
                } else {
                    (data.notifications as any[]).forEach(item => {
                        this.notifications.push(item);
                    });
                }
                this.check1 = true;
                this.lastPage = data.pagination.last_page;
            }, error => {
                this.check1 = true;
                this.dataGlobalService.actionFail(error.error);
            }
        );
    }

    private getListBirthdayInWeek() {
        this.check2 = false;
        this.sub1 = this.homeSevice.getBirthdayInWeek().subscribe(
            data => {
                this.employeeBirthDays = data.employees as any[];
                this.check2 = true;
            }, error => {
                this.check2 = true;
                this.dataGlobalService.actionFail(error.error);
            }
        );
    }


    private rememberLogin() {
        localStorage.setItem(constants.REMEMBER_LOGIN, 'logined');
    }

    showNews(news: any) {
        this.news = news;
        if (news.read === 0) {
            this.updateNewsRead(news);
        }
        $('#modalNews').modal('show');
    }

    updateNewsRead(row: any) {
        this.updateReadIsLoading = true;
        this.homeSevice.updateNews(row.id).subscribe(
            a => {
                this.notifications.forEach(item => {
                    if (item.id === row.id) {
                        item.read === 1 ? item.read = 0 : item.read = 1;
                        this.updateReadIsLoading = false;
                    }
                });
            }, b => {
                this.updateReadIsLoading = false;
            });
    }

    loadNewsMore() {
        this.noticePage = this.noticePage + 1;
        this.getListNotification();
    }

    gotoApproveTimeoff(id: any) {
        this.sub2 = this.timeOffService.showAnTimeOff(id).subscribe(
            data => {
                this.inforTimeOff = data.time_off;
                $('#Home-inforTimeOff').modal('show');
            }, error => {
                alert('Xảy ra lỗi!');
            }
        );
        /* if (type === 1) {
             this.router.navigate(['/lam-them-gio-va-nghi-phep/duyet-nghi-phep'], {
                 queryParams:
                     {
                         show_id: id,
                     }
             });
         }*/
    }


    homeRefuseTimeOff() {
        this.showSweet();
    }

    homeUpdateTimeOff() {
        let Ttype = '';
        if (this.inforTimeOff.status === 1 || this.inforTimeOff.status === 2 || this.inforTimeOff.status === 3) {
            Ttype = 'di-muon-ve-som';

        } else {
            Ttype = 'nghi-phep';
        }
        this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat'], {
            queryParams: {
                id: this.inforTimeOff.id,
                type: Ttype,
            }
        });
    }

    homeApproveTimeOff() {
        this.body.time_off_ids.push(this.inforTimeOff.id);
        swal({
            title: 'Đồng ý đơn xin phép?',
            text: 'Đơn sẽ được duyệt sau khi bạn đồng ý',
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
                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
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
                this.body.time_off_ids.push(this.inforTimeOff.id);
                this.timeOffService.refuseTimeOff(this.body).subscribe(
                    repo => {
                        swal('Thành công', 'Bạn đã từ chối đơn nghỉ phép', 'success').then(r => {
                        });


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

    homeDeleteTimeOff(): void {
        swal({
            title: 'Xóa đơn đăng ký này?',
            text: 'Lưu ý: Đơn đã được duyệt không thể xóa',
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
                    }, 1000);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            this.timeOffService.delete(this.inforTimeOff.id).subscribe(
                repo => {
                    this.getListNotification();
                    swal('Đã Xóa!', 'Bạn đã xóa thành công', 'success').then(r => {
                    }, e => {
                    });

                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }
}

