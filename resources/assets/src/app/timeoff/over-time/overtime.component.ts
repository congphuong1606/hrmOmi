import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/authSevice';
import {ActivatedRoute, NavigationEnd, Router} from '@angular/router';
import {DataGlobalService} from '../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';
import {Overtime} from '../../models/api/response/Overtime';
import {OvertimeService} from '../../services/overtime.service';

declare var $: any;
declare var swal: any;


@Component({
    selector: 'overtime-cmp',
    moduleId: module.id,
    templateUrl: 'overtime.component.html'
})

export class OvertimeComponent implements OnInit, OnDestroy {

    dataRows: any[] = [];
    employeeId = -1;
    curentPage = 1;
    total = 0;
    arrayPage: number[] = [];
    lastPage = 1;
    perPage = 15;
    form: FormGroup;
    isLoaded = false;
    subscription: Subscription;
    subscriptionDataRows: Subscription;
    sub: Subscription;
    otInfor: any;

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
        this.sub !== undefined ? this.sub.unsubscribe() : console.log(':D');
        this.subscriptionDataRows !== undefined ? this.subscriptionDataRows.unsubscribe() : console.log(':D');
    }

    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private authService: AuthService,
                private service: OvertimeService,
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
        if (!this.dataGlobalService.checkPemisson('/lam-them-gio-va-nghi-phep/lam-them-gio')) {
            window.history.back();
        } else {
            if (this.employeeId === -1) {
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
    }

    getUserinfo(): void {
        this.employeeId = this.dataGlobalService.getEmployId();
    }

    getDataRows(): void {
        this.subscriptionDataRows = this.service.getListForCurrentUser(this.curentPage).subscribe(
            data => {
                this.dataRows = (data.over_times as any[]);
                console.log(this.dataRows);
                this.curentPage = (data.pagination.current_page as number);
                this.lastPage = (data.pagination.last_page as number);
                this.total = (data.pagination.total as number);
                this.arrayPage.length = 0;
                for (let i = 1; i <= this.lastPage; i++) {
                    this.arrayPage.push(i);
                }
                this.isLoaded = true;


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
        // this.router.navigate(['/lam-them-gio-va-nghi-phep/cap-nhat-don-lam-them-gio'], {queryParams: data});
        this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-lam-them-gio'], {queryParams: {id: data.id}});
    }

    delete(row: any): void {
        swal({
            title: 'Xóa đăng ký làm ngoài giờ?',
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
            this.service.delete(row.id).subscribe(
                repo => {
                    this.dataRows = this.dataRows.filter(h => h.id !== row.id);
                    swal('Đã Xóa!', 'Bạn đã xóa thành công', 'success');
                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }


    creatTimeOff(): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-lam-them-gio']);
    }
    convertDateTime(time: string): string {
        const ddmmyyyy = time.split(' ')[0];
        const hhmm = time.split(' ')[1];
        const date = ddmmyyyy.split('-')[2] + '-' + ddmmyyyy.split('-')[1] + '-' + ddmmyyyy.split('-')[0]
        return date + ' ' + hhmm.split(':')[0] + ':' + hhmm.split(':')[1];
    }

    onBack(): void {
        this.ngOnDestroy()
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
        this.refreshPage();
    }

    refreshPage(): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/lam-them-gio'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    redirect_id: this.getRandomString()
                }
            });
    }


    reviewOT(id: any) {
        this.sub = this.service.get(id).subscribe(
            data => {
                console.log(data);
                this.otInfor = data.over_time;
                if (this.otInfor !== undefined) {
                    $('#reviewOT').modal('show');
                }
            },
            error1 => {
                this.dataGlobalService.actionFail(error1.error);
            }
        );
    }
}
