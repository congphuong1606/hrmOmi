import { Component, OnInit, AfterViewInit, OnDestroy, AfterViewChecked } from '@angular/core';
import { FormBuilder, FormGroup, FormControl } from '@angular/forms';
import { log } from 'util';
import { HttpClient } from '@angular/common/http';
import { AuthService } from '../../services/authSevice';
import { PersonnelService } from '../../services/personnel.service';
import { Employee, Pagination } from '../../models/api/response/ListEmployeesResponse';
import { Router, ActivatedRoute, NavigationEnd } from '@angular/router';
import { SearchEmployeeUpdateHistoryFormRequest } from '../../models/api/request/ListEmployeesRequest';
import { Subscription } from 'rxjs/Subscription';
import { LoaderController } from '../../shared/loader/loader';
import { DataGlobalService } from '../../services/data.global.service';

declare var $: any;
declare var swal: any;

@Component({
    selector: 'change-list-cmp',
    moduleId: module.id,
    templateUrl: 'change-list.component.html'
})

export class ChangelListComponent implements OnInit, AfterViewInit, OnDestroy, AfterViewChecked {
    form: FormGroup;
    isCheckAll: false;

    employees: Employee[];
    changeLists: Employee[];
    pagination: Pagination;
    listPage = [];
    listLimit = [5, 10, 15, 20, 30];
    queryParam: {
        limit: string,
        page: string,
    };
    routerSubcribe: Subscription;

    loaderController: LoaderController = new LoaderController();

    formRequest: SearchEmployeeUpdateHistoryFormRequest;

    listChecked = [];

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private router: Router,
        private route: ActivatedRoute,
        private dataGlobalService: DataGlobalService,
        private authService: AuthService) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.isCheckAll = false;
        this.resetQueryParam();
        this.employees = [];
        this.formRequest = new SearchEmployeeUpdateHistoryFormRequest();
        this.getQueryParamRouter();
        this.setFormRequestFromQueryParam();
    }

    ngAfterViewChecked() {
        $('[rel="tooltip"]').tooltip();
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/quan-ly-thay-doi')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.routerSubcribe = this.router.events.subscribe(val => {
                if (val instanceof NavigationEnd) {
                    this.getQueryParamRouter();
                    this.setFormRequestFromQueryParam();
                    this.getListEmployeeUpdateHistory();
                    this.loaderController.enableLoader();
                }
            });
            // this.router.routeReuseStrategy.shouldReuseRoute = function () {
            //     return false;
            // };
            this.loaderController.enableLoader();
            this.pagination = new Pagination();
            this.getQueryParamRouter();
            this.setFormRequestFromQueryParam();
            this.getListEmployeeUpdateHistory();
        }
    }

    ngOnDestroy(): void {
        this.routerSubcribe.unsubscribe();
        this.loaderController.cancelLoader();
    }

    checkAll() {
        if (this.isCheckAll) {
            this.changeLists.forEach((value, index) => {
                this.changeLists[index].checked = true;
            });
        } else {
            this.changeLists.forEach((value, index) => {
                this.changeLists[index].checked = false;
            });
        }
        this.setListChecked();
    }

    checkOne() {
        this.setListChecked();
    }


    setListChecked() {
        this.listChecked = [];
        this.changeLists.forEach((value, index) => {
            if (value.checked) {
                this.listChecked.push(value.id);
            }
        });
    }

    resetQueryParam() {
        this.queryParam = {
            limit: '',
            page: '',
        };
    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.queryParam.limit = queryParamMap.get('limit') === null ? '10' : queryParamMap.get('limit');
        this.queryParam.page = queryParamMap.get('page') === null ? '1' : queryParamMap.get('page');
    }


    getListEmployeeUpdateHistory() {
        this.loaderController.pushLoader();
        this.personnelService.getListChange(this.formRequest).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.changeLists = data.employees;
                this.pagination = data.pagination;
                this.listPage = [];
                for (let i = 1; i <= this.pagination.last_page; i++) {
                    const queryParamClone = Object.assign({}, this.queryParam);
                    queryParamClone.page = i + '';
                    this.listPage.push({
                        name: i,
                        queryParam: queryParamClone
                    });
                }
                this.setListChecked();
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    setFormRequestFromQueryParam() {
        this.formRequest.limit = this.queryParam.limit;
        this.formRequest.page = this.queryParam.page;
    }


    getRandomString() {
        let text = '';
        const possible = 'abcdefghijklmnopqrstuvwxyz0123456789';

        for (let i = 0; i < 5; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        return text;
    }

    refreshRouter() {
        this.router.navigate(['./danh-sach-nhan-su/quan-ly-thay-doi'], {
            queryParams: {
                limit: this.queryParam.limit,
                page: this.queryParam.page,
                redirect_id: this.getRandomString()
            }
        });
    }

    approve() {
        swal({
            title: 'Xác nhận phê duyệt',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve, reject) => {
                    this.personnelService.approveListChange(this.listChecked).subscribe(
                        data => {
                            resolve();
                        },
                        error => {
                            reject();
                            swal.showValidationError(
                                'Đã có lỗi xảy ra'
                            );
                        }
                    );
                });
            },
            allowOutsideClick: () => !swal.isLoading()
        }).then((result) => {
            if (result) {
                swal({
                    title: 'Thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                }).then((r) => {
                    this.refreshRouter();
                }).catch(() => {
                    this.refreshRouter();
                });
            }
        }).catch(swal.noop);

    }

    reject() {
        swal({
            title: 'Xác nhận từ chối',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve, reject) => {
                    this.personnelService.rejectListChange(this.listChecked).subscribe(
                        data => {
                            resolve();
                        },
                        error => {
                            reject();
                            swal.showValidationError(
                                'Đã có lỗi xảy ra'
                            );
                        }
                    );
                });
            },
            allowOutsideClick: () => !swal.isLoading()
        }).then((result) => {
            if (result) {
                swal({
                    title: 'Thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                }).then((r) => {
                    this.refreshRouter();
                }).catch(() => {
                    this.refreshRouter();
                });
            }
        }).catch(swal.noop);
    }

    ngAfterViewInit() {
        // Init Tooltips
        $('[rel="tooltip"]').tooltip();
    }
}
