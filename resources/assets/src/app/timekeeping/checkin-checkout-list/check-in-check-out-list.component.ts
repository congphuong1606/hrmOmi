import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd, ActivatedRoute } from '@angular/router';
import { PersonnelService } from "../../services/personnel.service";
import { LoaderController } from "../../shared/loader/loader";
import { EmployeeExcelDepartment, Department, EmployeeExcelFile, EmployeeExcelFileDetail, TimeKeepingExcelFileDetail } from "../../models/api/response/ListEmployeesResponse";
import { DataGlobalService } from "../../services/data.global.service";

declare var $: any;
declare var swal: any;

@Component({
    selector: 'check-in-check-out-list-cmp',
    moduleId: module.id,
    templateUrl: 'check-in-check-out-list.component.html'
})

export class CheckInCheckOutListComponent implements OnInit {
    form: FormGroup;
    uploadFileForm: FormGroup;
    isCheckAll: false;
    loaderController: LoaderController = new LoaderController();
    token;
    id;

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private authService: AuthService,
        private route: ActivatedRoute,
        private globalDataService: DataGlobalService,
        private router: Router) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.token = this.authService.getToken();
    }

    ngOnInit() {
        if (!this.globalDataService.checkPemisson('/cham-cong/check-in-check-out')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.loaderController.enableLoader();
            this.getListTimeKeepingMonths();
        }

    }
    selectedTime: any;
    showIt = false;

    onCloseModalTime(selected) {
        if (selected !== null) {
            this.timekeepings.forEach((element, key) => {
                if (element.id === selected.id) {
                    this.timekeepings[key] = selected;
                }
            });
        }
        this.showIt = false;
    }
    openModalTime(time) {
        this.showIt = true;
        this.selectedTime = time;
    }

    paginationLimit = 30;
    paginationPage = 0;
    paginationCurrentPage = 0;
    paginationListPage = [];

    listMonths = [];
    selectedMonth: any = null;
    timekeepings: any = [];
    file: TimeKeepingExcelFileDetail = new TimeKeepingExcelFileDetail();
    employeeName = '';
    employeeCode = '';
    getListTimeKeepingMonths() {
        this.loaderController.pushLoader();
        this.personnelService.getListTimeKeepingMonths().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listMonths = data.months;
                if (this.listMonths.length) {
                    this.selectedMonth = this.listMonths[0];
                }
                if (this.selectedMonth !== null) {
                    this.getTimeKeepingMonth();
                }
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    getTimeKeepingMonth() {
        if (this.selectedMonth === null) {
            return;
        };

        this.loaderController.pushLoader();
        this.personnelService.getTimeKeepingMonth(this.selectedMonth, this.employeeName, this.employeeCode, this.paginationCurrentPage).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.timekeepings = data.time_ons;
                if (this.timekeepings.length) {
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
            }
        );
    }

    gotoFirstPage() {
        this.paginationCurrentPage = 1;
        this.getTimeKeepingMonth();
    }

    gotoLastPage() {
        this.paginationCurrentPage = this.paginationPage;
        this.getTimeKeepingMonth();
    }

    nextPage() {
        if (this.paginationCurrentPage < this.paginationPage) {
            this.paginationCurrentPage = this.paginationCurrentPage + 1;
            this.getTimeKeepingMonth();
        }
    }

    prevPage() {
        if (this.paginationCurrentPage > 1) {
            this.paginationCurrentPage = this.paginationCurrentPage - 1;
            this.getTimeKeepingMonth();
        }
    }

    paginate(pn) {
        this.paginationCurrentPage = pn;
        this.getTimeKeepingMonth();
    }

    parse() {
        this.loaderController.pushLoader();
        this.personnelService.parseTimeKeepingExcelFile(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.file = data.file;
                if (this.file.data.length) {
                    this.paginationCurrentPage = 1;

                    this.paginationPage = Math.ceil(this.file.data.length / this.paginationLimit);
                    for (let i = 1; i <= this.paginationPage; i++) {
                        this.paginationListPage.push(i);
                    }
                    this.paginationCurrentPage = 1;
                    this.getTimeKeepingMonth();
                };
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

}
