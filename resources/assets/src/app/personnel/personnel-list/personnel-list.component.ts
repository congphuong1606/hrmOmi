import { Component, OnInit, AfterViewInit, OnDestroy, AfterViewChecked } from '@angular/core';
import { FormBuilder, FormGroup, FormControl } from '@angular/forms';
import { log } from 'util';
import { HttpClient } from '@angular/common/http';
import { AuthService } from '../../services/authSevice';
import { Router, NavigationEnd, ActivatedRoute } from '@angular/router';
import { PersonnelService } from '../../services/personnel.service';
import { Employee, Pagination, JobStatus, WorkingStatus } from '../../models/api/response/ListEmployeesResponse';
import { SearchEmployeeFormRequest, Department, Position } from '../../models/api/request/ListEmployeesRequest';
import { Subscription } from 'rxjs/Subscription';
import { LoaderController } from '../../shared/loader/loader';
import { DataGlobalService } from '../../services/data.global.service';

declare var $: any;
declare var swal: any;

@Component({
    selector: 'personnel-cmp',
    moduleId: module.id,
    templateUrl: 'personnel-list.component.html'
})

export class PersonnelListComponent implements OnInit, AfterViewInit, OnDestroy, AfterViewChecked {

    form: FormGroup;
    routerSubcribe: Subscription;

    loaderController: LoaderController = new LoaderController();

    employees: Employee[];
    pagination: Pagination;

    name = '';
    id = '';
    advanced_search: any;
    job_status: any;
    position: any;
    department: any;

    status: any;
    listPage = [];
    working_status;

    department_id;
    job_status_id;
    position_id;
    working_status_id;
    page = '1';
    limit;

    isAlowViewInfo = false;
    isAlowUpdate = false;
    isAlowAdd = false;

    queryParam: {
        id: string,
        name: string,
        department_id: string,
        job_status_id: string,
        position_id: string,
        working_status_id: string,
        limit: string,
        page: string,
        advanced_search: string,
    };

    listJobStatus: JobStatus[];
    listWorkingStatus: WorkingStatus[];
    listDepartments: Department[];
    listPositions: Position[];
    listLimit = [5, 10, 15, 20, 30];
    formRequest = new SearchEmployeeFormRequest();
    isAdmin = false;
    employeeId = 0;

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private authService: AuthService,
        private route: ActivatedRoute,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.resetQueryParam();
        this.formRequest = new SearchEmployeeFormRequest();
        this.getQueryParamRouter();
        this.setFormRequestFromQueryParam();
        this.queryParamLast = Object.assign({}, this.queryParam);
    }

    ngAfterViewChecked() {
        $('[rel="tooltip"]').tooltip();
    }

    ngOnInit() {
        this.isAdmin = this.dataGlobalService.isAdmin();
        this.isAlowViewInfo = this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/thong-tin-nhan-su');
        this.isAlowUpdate = this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/sua-thong-tin-nhan-su');
        this.isAlowAdd = this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/them-nhan-su');
        this.isAdmin = this.dataGlobalService.isAdmin();
        this.employeeId = this.dataGlobalService.getEmployId();
        this.routerSubcribe = this.router.events.subscribe(val => {
            if (val instanceof NavigationEnd) {
                this.getQueryParamRouter();
                this.setFormRequestFromQueryParam();
                this.getListEmployees();
                this.loaderController.enableLoader();
            }
        });
        this.loaderController.enableLoader();
        this.pagination = new Pagination();
        this.getQueryParamRouter();
        this.setFormRequestFromQueryParam();
        this.getListEmployees();
        this.getListDefine();
    }

    ngOnDestroy(): void {
        this.routerSubcribe.unsubscribe();
        this.loaderController.cancelLoader();
    }

    getListDefine() {
        this.getListJobStatus();
        this.getListWorkingStatus();
        this.getListDepartments();
        this.getListPositions();
        if (this.queryParam.limit !== '') {
            this.listLimit.forEach((value, key) => {
                if (value === parseInt(this.queryParam.limit, 10)) {
                    this.limit = this.listLimit[key];
                }
            });
        } else {
            this.limit = this.listLimit[1];
        }
        this.id = this.queryParam.id;
        this.name = this.queryParam.name;
        this.advanced_search = this.queryParam.advanced_search;
        if (this.queryParam.advanced_search === 'true') {
            $('.slide').css('display', 'block');
        } else {
            $('.slide').css('display', 'none');
        }

    }

    search() {
        this.setFormRequestFromSearch();
        const data = {
            queryParams: {
                user_id: this.id,
                full_name: this.name,
                department_id: this.department.id,
                job_status_id: this.job_status.id,
                position_id: this.position.id,
                working_status_id: this.working_status.id,
                limit: this.limit,
                page: this.page,
                advanced_search: this.advanced_search,
            }
        };
        console.log(data);
        this.router.navigate(['./danh-sach-nhan-su/danh-sach'], data);
    }
    queryParamLast : any = {};
    gotoFirstPage() {
        const query = Object.assign({}, this.queryParam);
        query.page = '1';
        this.router.navigate(['./danh-sach-nhan-su/danh-sach'], {queryParams: query});
    }
    gotoPrevPage() {
        const query = Object.assign({}, this.queryParam);
        query.page = parseInt(query.page, 10) - 1 <= 0 ? '0' : (parseInt(query.page, 10) - 1) + '';
        this.router.navigate(['./danh-sach-nhan-su/danh-sach'], {queryParams: query});
    }
    gotoNextPage() {
        const query = Object.assign({}, this.queryParam);
        query.page = (parseInt(query.page, 10) + 1) + '';
        this.router.navigate(['./danh-sach-nhan-su/danh-sach'], {queryParams: query});
    }
    gotoLastPage() {
        this.router.navigate(['./danh-sach-nhan-su/danh-sach'], {queryParams: this.queryParamLast});
        console.log(this.queryParamLast);
    }
    setFormRequestFromSearch() {
        this.formRequest.working_status = this.working_status.id;
        this.formRequest.id = this.id;
        this.formRequest.department = this.department.id;
        this.formRequest.job_status = this.job_status.id;
        this.formRequest.limit = this.limit;
        this.formRequest.name = this.name;
        this.formRequest.page = '1';
        this.formRequest.position = this.position.id;
        this.formRequest.advanced_search = this.advanced_search;
    }

    setFormRequestFromQueryParam() {
        this.formRequest.working_status = this.queryParam.working_status_id;
        this.formRequest.id = this.queryParam.id;
        this.formRequest.department = this.queryParam.department_id;
        this.formRequest.job_status = this.queryParam.job_status_id;
        this.formRequest.limit = this.queryParam.limit;
        this.formRequest.name = this.queryParam.name;
        this.formRequest.page = this.queryParam.page;
        this.formRequest.position = this.queryParam.position_id;
        this.formRequest.advanced_search = this.queryParam.advanced_search;
    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.queryParam.id = queryParamMap.get('user_id') === null ? '' : queryParamMap.get('user_id');
        this.queryParam.name = queryParamMap.get('full_name') === null ? '' : queryParamMap.get('full_name');
        this.queryParam.advanced_search = queryParamMap.get('advanced_search') === null ? '' : queryParamMap.get('advanced_search');
        this.queryParam.department_id = queryParamMap.get('department_id') === null ? '' : queryParamMap.get('department_id');
        this.queryParam.job_status_id = queryParamMap.get('job_status_id') === null ? '' : queryParamMap.get('job_status_id');
        this.queryParam.position_id = queryParamMap.get('position_id') === null ? '' : queryParamMap.get('position_id');
        this.queryParam.working_status_id = queryParamMap.get('working_status_id') === null ? '' : queryParamMap.get('working_status_id');
        this.queryParam.limit = queryParamMap.get('limit') === null ? '' : queryParamMap.get('limit');
        this.queryParam.page = queryParamMap.get('page') === null ? '1' : queryParamMap.get('page');
    }

    resetQueryParam() {
        this.queryParam = {
            id: '',
            name: '',
            department_id: '',
            job_status_id: '',
            position_id: '',
            working_status_id: '',
            limit: '',
            page: '',
            advanced_search: '',
        };
    }

    getListEmployees() {
        this.loaderController.pushLoader();
        this.personnelService.getListEmployees(this.formRequest).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.employees = data.employees;
                this.pagination = data.pagination;
                this.queryParamLast.page = this.pagination.last_page + '';
                this.listPage = [];
                for (let i = 1; i <= this.pagination.last_page; i++) {
                    const queryParamClone: any = Object.assign({}, this.queryParam);
                    queryParamClone.full_name = this.queryParam.name;
                    queryParamClone.user_id = this.queryParam.id;
                    queryParamClone.page = i + '';
                    this.listPage.push({
                        name: i,
                        queryParam: queryParamClone
                    });
                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    setDefaultListJobStatus() {
        this.listJobStatus = [];
        const defaultJobStatus = new JobStatus();
        defaultJobStatus.name = 'Tất cả';
        this.listJobStatus.push(defaultJobStatus);
    }

    getListJobStatus() {
        this.setDefaultListJobStatus();
        this.loaderController.pushLoader();
        this.personnelService.getListJobStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listJobStatus.push(...data.jobs_status);
                if (this.listJobStatus.length) {
                    if (this.queryParam.working_status_id !== '') {
                        this.listJobStatus.forEach((value, key) => {
                            if (value.id === parseInt(this.queryParam.job_status_id, 10)) {
                                this.job_status = this.listJobStatus[key];
                            }
                        });
                    } else {
                        this.job_status = this.listJobStatus[0];
                    }
                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    setDefaultListWorkingStatus() {
        this.listWorkingStatus = [];
        const defaultWorkingStatus = new WorkingStatus();
        defaultWorkingStatus.name = 'Tất cả';
        this.listWorkingStatus.push(defaultWorkingStatus);
    }

    getListWorkingStatus() {
        this.setDefaultListWorkingStatus();
        this.loaderController.pushLoader();
        this.personnelService.getListWorkingStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listWorkingStatus.push(...data.working_status);
                if (this.listWorkingStatus.length) {
                    if (this.queryParam.working_status_id !== '') {
                        this.listWorkingStatus.forEach((value, key) => {
                            if (value.id === parseInt(this.queryParam.working_status_id, 10)) {
                                this.working_status = this.listWorkingStatus[key];
                            }
                        });
                    } else {
                        this.working_status = this.listWorkingStatus[0];
                    }
                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    setDefaultListPositions() {
        this.listPositions = [];
        const defaultPositions = new Position();
        defaultPositions.name = 'Tất cả';
        this.listPositions.push(defaultPositions);
    }

    getListPositions() {
        this.setDefaultListPositions();
        this.loaderController.pushLoader();
        this.personnelService.getListPositions().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listPositions.push(...data.positions);
                if (this.listPositions.length) {
                    if (this.queryParam.position_id !== '') {
                        this.listPositions.forEach((value, key) => {
                            if (value.id === parseInt(this.queryParam.position_id, 10)) {
                                this.position = this.listPositions[key];
                            }
                        });
                    } else {
                        this.position = this.listPositions[0];
                    }

                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    setDefaultListDepartments() {
        this.listDepartments = [];
        const defaultDepartments = new Department();
        defaultDepartments.name = 'Tất cả';
        this.listDepartments.push(defaultDepartments);
    }

    getListDepartments() {
        this.setDefaultListDepartments();
        this.loaderController.pushLoader();
        this.personnelService.getListDeparments().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listDepartments.push(...data.departments);
                if (this.listDepartments.length) {
                    if (this.queryParam.department_id !== '') {
                        this.listDepartments.forEach((value, key) => {
                            if (value.id === parseInt(this.queryParam.department_id, 10)) {
                                this.department = this.listDepartments[key];
                            }
                        });
                    } else {
                        this.department = this.listDepartments[0];
                    }
                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    goToPersonnelCreate() {
        this.router.navigate(['/danh-sach-nhan-su/them-nhan-su']);
    }

    toggle() {
        $('.slide').slideToggle(() => {
            if ($('.slide').is(':visible')) {
                this.advanced_search = 'true';
            } else {
                this.advanced_search = 'false';
            }
        });
    }

    deletePersonnel(employee) {
        swal({
            title: 'Xác nhận',
            text: 'Bạn có muốn xóa nhân sự này?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không',
            showLoaderOnConfirm: true,
            preConfirm: (email) => {
                return new Promise((resolve) => {

                    this.personnelService.deletePersonnel(employee.id).subscribe(
                        data => {
                            const index = this.employees.indexOf(employee);
                            this.employees.splice(index, 1);
                            resolve();
                        },
                        error => {
                            console.log(error);
                            swal.showValidationError(
                                'Đã có lỗi xảy ra'
                            );
                        }
                    );
                });
            },
            allowOutsideClick: () => !swal.isLoading()
        }).then((result) => {
            if (result.value) {
                swal({
                    type: 'success',
                    title: 'Ajax request finished!',
                    html: 'Submitted email: ' + result.value
                });
            }
        }).catch(swal.noop);

    }

    ngAfterViewInit() {
        // Init Tooltips
        $('[rel="tooltip"]').tooltip();
    }
}
