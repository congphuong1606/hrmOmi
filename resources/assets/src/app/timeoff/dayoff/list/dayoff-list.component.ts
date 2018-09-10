import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router, NavigationEnd, ActivatedRoute} from '@angular/router';
import {TimeOffService} from '../../../services/time-off/TimeOff.service';
import {DayOff} from '../../../models/api/response/TimeOffReponse';
import {DataGlobalService} from '../../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';

declare var $: any;
declare var swal: any;

@Component({
    selector: 'dayoff-list-cmp',
    moduleId: module.id,
    templateUrl: 'dayoff-list.component.html'
})

export class DayoffListComponent implements OnInit, OnDestroy {

    dataRows: DayOff[] = [];
    employeeId = -1;
    curentPage = 1;
    totalDayOff = 0;
    arrayPage: number[] = [];
    lastPage = 1;
    perPage = 15;
    form: FormGroup;
    isLoaded = false;
    subscription: Subscription;
    subscriptionDataRows: Subscription;
     timOffInfor: DayOff;

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
        this.subscriptionDataRows !== undefined ? this.subscriptionDataRows.unsubscribe() : console.log(':D');
    }

    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private authService: AuthService,
                private timeOffService: TimeOffService,
                private dataGlobalService: DataGlobalService,
                private route: ActivatedRoute,
                private router: Router) {

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
        if (!this.dataGlobalService.checkPemisson('/lam-them-gio-va-nghi-phep/nghi-theo-ngay')) {
            window.history.back();
        } else {
            if (this.employeeId === -1) {
                this.getUserinfo();
                this.getDataRows();
            }
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
        this.subscriptionDataRows = this.timeOffService.getListDayOff(this.curentPage).subscribe(
            data => {
                this.dataRows = (data.time_off as DayOff[]);
                console.log(this.dataRows);
                this.curentPage = (data.pagination.current_page as number);
                if (this.dataRows.length === 0 && this.curentPage >= 2) {
                    this.curentPage = this.curentPage - 1;
                    this.refreshPage();
                } else {
                    this.lastPage = (data.pagination.last_page as number);
                    this.totalDayOff = (data.pagination.total as number);
                    this.arrayPage.length = 0;
                    for (let i = 1; i <= this.lastPage; i++) {
                        this.arrayPage.push(i);
                    }
                    this.isLoaded = true;
                }

            },
            error => {
                console.log(error.error)
                this.isLoaded = true;
                this.dataGlobalService.actionFail(error.error);
                // swal({ title: 'lỗi'});
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
                type: 'nghi-phep',
            }
        });
    }

    showInforTimeOff(row: DayOff) {
        this.timOffInfor = row;
        if (this.timOffInfor !== undefined) {
            $('#inforDayOff').modal('show');
        }
    }

    delete(row: any): void {
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
            this.timeOffService.delete(row.id).subscribe(
                repo => {
                    this.refreshPage();
                    swal('Đã Xóa!', 'Bạn đã xóa thành công', 'success').then(r => {
                    }, e => {
                    });

                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }


    creatTimeOff(): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat'], {
            queryParams: {
                type: 'nghi-phep',
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
        this.router.navigate(['/lam-them-gio-va-nghi-phep/nghi-theo-ngay'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    redirect_id: this.getRandomString()
                }
            });
    }

    refreshPage(): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/nghi-theo-ngay'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    redirect_id: this.getRandomString()
                }
            });
    }

}
