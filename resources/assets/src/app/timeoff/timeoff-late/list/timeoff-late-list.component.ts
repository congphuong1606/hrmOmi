import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router, NavigationEnd, ActivatedRoute} from '@angular/router';
import {DayOff, TimeOff} from '../../../models/api/response/TimeOffReponse';
import {TimeOffService} from '../../../services/time-off/TimeOff.service';
import {DataGlobalService} from '../../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';

declare var $: any;
declare var swal: any;

@Component({
    selector: 'timeoff-late-list-cmp',
    moduleId: module.id,
    templateUrl: 'timeoff-late-list.component.html'
})

export class TimeoffLateListComponent implements OnInit, OnDestroy {

    dataRows: TimeOff[] = [];
    employeeId = -1;
    curentPage = 1;
    totalTimeOff = 0;
    arrayPage: number[] = [];
    lastPage = 1;
    perPage = 15;
    form: FormGroup;
    subscription: Subscription;
    subscriptionDataRows: Subscription;
    isLoaded = false;
    timOffInfor: TimeOff;

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
                if (this.employeeId !== -1) {
                    this.getDataRows();
                }
            }
        });
        this.getQueryParamRouter();
        if (!this.dataGlobalService.checkPemisson('/lam-them-gio-va-nghi-phep/di-muon-ve-som')) {
            window.history.back();
        } else {
            if (this.employeeId === -1) {
                this.getUserinfo();
                this.getDataRows();
            }
        }

    }

    showInforTimeOff(row: TimeOff) {
        this.timOffInfor = row;
        if (this.timOffInfor !== undefined) {
            $('#inforTimeOff').modal('show');
        }
    }

    convertDateTime(time: string): string {
        const ddmmyyyy = time.split(' ')[0];
        const hhmm = time.split(' ')[1];
        const date = ddmmyyyy.split('-')[2] + '-' + ddmmyyyy.split('-')[1] + '-' + ddmmyyyy.split('-')[0]
        return date + ' ' + hhmm.split(':')[0] + ':' + hhmm.split(':')[1];
    }

    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }


    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.curentPage = parseInt(queryParamMap.get('index_page') === null ? '1' : queryParamMap.get('index_page'));
    }

    getUserinfo(): void {
        this.employeeId = this.dataGlobalService.getEmployId();

    }

    getDataRows(): void {
        this.subscriptionDataRows = this.timeOffService.getListTimeOff(this.curentPage).subscribe(
            data => {
                this.dataRows = (data.time_off as TimeOff[]);
                this.curentPage = (data.pagination.current_page as number);
                if (this.dataRows.length === 0 && this.curentPage >= 2) {
                    this.curentPage = this.curentPage - 1;
                    this.refreshPage();
                } else {
                    this.lastPage = (data.pagination.last_page as number);
                    this.totalTimeOff = (data.pagination.total as number);
                    this.arrayPage.length = 0;
                    for (let i = 1; i <= this.lastPage; i++) {
                        this.arrayPage.push(i);
                    }
                    this.isLoaded = true;
                }

            },
            error => {
                this.isLoaded = true;
                this.dataGlobalService.actionFail(error.error);
            }
        );
    }


    isLoadSuccess(): boolean {
        if (this.isLoaded) {
            $('[rel="tooltip"]').tooltip();
        }
        return this.isLoaded;
    }

    gotoUpdate(data: any): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat'], {
            queryParams: {
                id: data.id,
                type: 'di-muon-ve-som',
            }
        });
    }

    delete(row: any): void {
        swal({
            title: 'Xóa đơn đăng ký này?',
            text: 'Lưu ý: Đơn đã được duyệt không thể hủy',
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
            this.timeOffService.delete(row.id).subscribe(
                repo => {
                    this.refreshPage();
                    swal('Đã Xóa!', 'Bạn đã xóa thành công', 'success').then(r => {
                    }, e => {
                    });

                }, error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }


    creatTimeOff(): void {
        // this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-don-dang-ky', this.employeeId, 'di-muon-ve-som']);
        this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat'], {
            queryParams: {
                type: 'di-muon-ve-som',
            }
        });
    }

    onBack(): void {
        window.history.back();
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
        this.router.navigate(['/lam-them-gio-va-nghi-phep/di-muon-ve-som'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    redirect_id: this.getRandomString()
                }
            });
    }

    refreshPage(): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/di-muon-ve-som'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    redirect_id: this.getRandomString()
                }
            });
    }
}
