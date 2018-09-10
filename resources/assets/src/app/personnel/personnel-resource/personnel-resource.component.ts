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
    selector: 'personnel-resource-cmp',
    moduleId: module.id,
    templateUrl: 'personnel-resource.component.html'
})

export class PersonnelResourceComponent implements OnInit, AfterViewInit, OnDestroy, AfterViewChecked {

    form: FormGroup;
    routerSubcribe: Subscription;

    loaderController: LoaderController = new LoaderController();

    employees: Employee[];
    paginationLimit = 30;
    paginationPage = 0;
    paginationCurrentPage = 0;
    paginationListPage = [];

    employee_name = '';
    direct_manager_name = '';
    project_manager_name = '';
    advanced_search: any = false;
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
    page = 1;
    limit;

    isAlowViewInfo = false;
    isAlowUpdate = false;
    isAlowAdd = false;

    listJobStatus: JobStatus[];
    listWorkingStatus: WorkingStatus[];
    listDepartments: Department[];
    listPositions: Position[];
    listLimit = [5, 10, 15, 20, 30];
    formRequest: {
        employee_name: string,
        direct_manager_name: string,
        project_manager_name: string,
        department_id: number,
        job_status_id: number,
        position_id: number,
        working_status_id: number,
        limit: number,
        page: number,
        advanced_search: boolean,
    };
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
    }

    ngAfterViewChecked() {
        $('[rel="tooltip"]').tooltip();
    }

    gotoFirstPage() {
        this.paginationCurrentPage = 1;
        this.search();
    }

    gotoLastPage() {
        this.paginationCurrentPage = this.paginationPage;
        this.search();
    }

    nextPage() {
        if (this.paginationCurrentPage < this.paginationPage) {
            this.paginationCurrentPage = this.paginationCurrentPage + 1;
            this.search();
        }
    }

    prevPage() {
        if (this.paginationCurrentPage > 1) {
            this.paginationCurrentPage = this.paginationCurrentPage - 1;
            this.search();
        }
    }

    paginate(pn) {
        this.paginationCurrentPage = pn;
        this.search();
    }

    showModalDirectManager = false;

    onCloseModalDirectManager(selectedEmployeeDirectManager) {
        if (selectedEmployeeDirectManager !== null) {
            this.employees.forEach((element, key) => {
                if (element.id === selectedEmployeeDirectManager.id) {
                    this.employees[key].direct_manager = selectedEmployeeDirectManager.direct_manager;
                }
            });
        }
        this.showModalDirectManager = false;
    }
    selectedEmployeeDirectManager: any;
    openModalDirectManager(employeeDirectManager) {
        this.showModalDirectManager = true;
        this.selectedEmployeeDirectManager = employeeDirectManager;
    }

    showModalProjectManager = false;

    onCloseModalProjectManager(selectedEmployeeProjectManager) {
        if (selectedEmployeeProjectManager !== null) {
            this.employees.forEach((element, key) => {
                if (element.id === selectedEmployeeProjectManager.id) {
                    this.employees[key].project_managers = selectedEmployeeProjectManager.project_managers;
                }
            });
        }
        this.showModalProjectManager = false;
    }
    selectedEmployeeProjectManager: any;
    openModalProjectManager(employeeProjectManager) {
        this.showModalProjectManager = true;
        this.selectedEmployeeProjectManager = employeeProjectManager;
    }

    ngOnInit() {
        this.isAdmin = this.dataGlobalService.isAdmin();
        if (!this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/nguon-luc')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.employeeId = this.dataGlobalService.getEmployId();
            this.loaderController.enableLoader();
            this.getListDefine();
            this.formRequest = {
                employee_name: this.employee_name,
                direct_manager_name: this.direct_manager_name,
                project_manager_name: this.project_manager_name,
                job_status_id: this.job_status.id,
                working_status_id: this.working_status.id,
                department_id: this.department.id,
                position_id: this.position.id,
                advanced_search: this.advanced_search,
                limit: this.limit,
                page: this.page,
            }
            this.search();
        }
    }

    ngOnDestroy(): void {
        this.loaderController.cancelLoader();
    }

    getListDefine() {
        this.getListJobStatus();
        this.getListWorkingStatus();
        this.getListDepartments();
        this.getListPositions();
        this.limit = this.listLimit[1];
        if (this.advanced_search) {
            $('.slide').css('display', 'block');
        } else {
            $('.slide').css('display', 'none');
        }

    }

    search() {
        this.formRequest.employee_name = this.employee_name;
        this.formRequest.direct_manager_name = this.direct_manager_name;
        this.formRequest.project_manager_name = this.project_manager_name;
        this.formRequest.job_status_id = this.job_status.id;
        this.formRequest.working_status_id = this.working_status.id;
        this.formRequest.department_id = this.department.id;
        this.formRequest.position_id = this.position.id;
        this.formRequest.advanced_search = this.advanced_search;
        this.formRequest.limit = this.paginationLimit;
        this.formRequest.page = this.paginationCurrentPage;
        this.getListEmployeesResource();
    }


    getListEmployeesResource() {
        this.loaderController.pushLoader();
        this.personnelService.getListEmployeesResource(this.formRequest).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.employees = data.employees;
                if (this.employees.length) {
                    this.paginationCurrentPage = data.pagination.current_page;
                    this.paginationPage = data.pagination.last_page;
                    this.paginationListPage = [];
                    for (let i = 1; i <= this.paginationPage; i++) {
                        this.paginationListPage.push(i);
                    }
                } else {
                    this.paginationListPage = [];
                    this.paginationPage = 0;
                    this.paginationCurrentPage = 0;
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
        this.job_status = this.listJobStatus[0];
    }

    getListJobStatus() {
        this.setDefaultListJobStatus();
        this.loaderController.pushLoader();
        this.personnelService.getListJobStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listJobStatus.push(...data.jobs_status);
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
        this.working_status = this.listWorkingStatus[0];
    }

    getListWorkingStatus() {
        this.setDefaultListWorkingStatus();
        this.loaderController.pushLoader();
        this.personnelService.getListWorkingStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listWorkingStatus.push(...data.working_status);
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
        this.position = this.listPositions[0];
    }

    getListPositions() {
        this.setDefaultListPositions();
        this.loaderController.pushLoader();
        this.personnelService.getListPositions().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listPositions.push(...data.positions);
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
        this.department = this.listDepartments[0];
    }

    getListDepartments() {
        this.setDefaultListDepartments();
        this.loaderController.pushLoader();
        this.personnelService.getListDeparments().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listDepartments.push(...data.departments);
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    toggle() {
        $('.slide').slideToggle(() => {
            if ($('.slide').is(':visible')) {
                this.advanced_search = true;
            } else {
                this.advanced_search = false;
            }
        });
    }

    ngAfterViewInit() {
        // Init Tooltips
        $('[rel="tooltip"]').tooltip();
    }
}
