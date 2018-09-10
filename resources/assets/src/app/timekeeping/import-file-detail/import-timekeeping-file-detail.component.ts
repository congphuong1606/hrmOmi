import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd, ActivatedRoute } from '@angular/router';
import { PersonnelService } from "../../services/personnel.service";
import { LoaderController } from "../../shared/loader/loader";
import { EmployeeExcelDepartment, Department, EmployeeExcelFile, EmployeeExcelFileDetail, TimeKeepingExcelFileDetail } from "../../models/api/response/ListEmployeesResponse";

declare var $: any;
declare var swal: any;

@Component({
    selector: 'import-timekeeping-file-detail-cmp',
    moduleId: module.id,
    templateUrl: 'import-timekeeping-file-detail.component.html'
})

export class ImportTimeKeepingFileDetailComponent implements OnInit {
    form: FormGroup;
    uploadFileForm: FormGroup;
    isCheckAll = false;
    isCheckAllUnOverride = false;
    isCheckAllOverride = false;
    loaderController: LoaderController = new LoaderController();
    token;
    id;

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private authService: AuthService,
        private route: ActivatedRoute,
        private router: Router) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.token = this.authService.getToken();
    }

    ngOnInit() {
        this.id = this.route.snapshot.paramMap.get('id');
        this.loaderController.enableLoader();
        this.getTimeKeepingExcelFile();
    }

    checkAll() {
        if (this.isCheckAll) {
            this.file.data.forEach((value, index) => {
                this.file.data[index].is_checked = true;
            });
        } else {
            this.file.data.forEach((value, index) => {
                this.file.data[index].is_checked = false;
            });
        }
        this.setListChecked();
    }

    checkAllUnOverride() {
        if (this.isCheckAllUnOverride) {
            this.file.data.forEach((value, index) => {
                if (this.file.data[index].time_on_id !== null) {
                    this.file.data[index].is_checked = true;
                }
            });
        } else {
            this.file.data.forEach((value, index) => {
                if (this.file.data[index].time_on_id !== null) {
                    this.file.data[index].is_checked = false;
                }
            });
        }
        this.setListChecked();
    }

    checkAllOverride() {
        if (this.isCheckAllOverride) {
            this.file.data.forEach((value, index) => {
                if (this.file.data[index].time_on_id === null) {
                    this.file.data[index].is_checked = true;
                }
            });
        } else {
            this.file.data.forEach((value, index) => {
                if (this.file.data[index].time_on_id === null) {
                    this.file.data[index].is_checked = false;
                }
            });
        }
        this.setListChecked();
    }

    checkOne(employee) {
        this.setListChecked();
    }
    listChecked = [];
    setListChecked() {
        this.listChecked = [];
        this.file.data.forEach((value, index) => {
            if (value.is_checked && !value.is_duplicate && !value.is_accepted) {
                this.listChecked.push(value.id);
            }
        });
    }

    paginationLimit = 30;
    paginationPage = 0;
    paginationCurrentPage = 0;
    paginationListPage = [];

    file: TimeKeepingExcelFileDetail = new TimeKeepingExcelFileDetail();
    getTimeKeepingExcelFile() {
        this.loaderController.pushLoader();
        this.personnelService.getTimeKeepingExcelFile(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.file = data.file;
                console.log(this.file);
                if (this.file.data.length) {
                    this.paginationCurrentPage = 1;

                    this.paginationPage = Math.ceil(this.file.data.length / this.paginationLimit);
                    for (let i = 1; i <= this.paginationPage; i++) {
                        this.paginationListPage.push(i);
                    }
                    this.paginate(1);
                };
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    gotoFirstPage() {
        this.paginate(1);
    }

    gotoLastPage() {
        this.paginate(this.paginationPage);
    }

    nextPage() {
        if (this.paginationCurrentPage < this.paginationPage) {
            this.paginate(this.paginationCurrentPage + 1);
        }
    }

    prevPage() {
        if (this.paginationCurrentPage > 1) {
            this.paginate(this.paginationCurrentPage - 1);
        }
    }

    paginate(page) {
        this.paginationCurrentPage = page;
        this.file.data.forEach((element, index) => {
            if (index >= (this.paginationCurrentPage - 1) * this.paginationLimit &&
                index < (this.paginationCurrentPage * this.paginationLimit)) {
                element.showing = true;
            } else {
                element.showing = false;
            }
        });
    }

    parse() {
        this.loaderController.pushLoader();
        this.personnelService.parseTimeKeepingExcelFile(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.file = data.file;
                this.paginationListPage = [];
                this.paginationPage = 0;
                this.paginationCurrentPage = 0;
                if (this.file.data.length) {
                    this.paginationCurrentPage = 1;
                    this.paginationPage = Math.ceil(this.file.data.length / this.paginationLimit);
                    for (let i = 1; i <= this.paginationPage; i++) {
                        this.paginationListPage.push(i);
                    }
                    this.paginate(1);
                };
            },
            error => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Đã có lỗi xảy ra',
                    text: error.error,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                }).then((result) => {
                }).catch(swal.noop);
            }
        );
    }

    apply() {
        this.loaderController.pushLoader();
        this.personnelService.applyTimekeepingExcelFile(this.id, this.listChecked).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.file = data.file;
                this.paginationListPage = [];
                this.paginationPage = 0;
                this.paginationCurrentPage = 0;
                if (this.file.data.length) {
                    this.paginationCurrentPage = 1;
                    this.paginationPage = Math.ceil(this.file.data.length / this.paginationLimit);
                    for (let i = 1; i <= this.paginationPage; i++) {
                        this.paginationListPage.push(i);
                    }
                    this.paginate(1);
                };
            },
            error => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Đã có lỗi xảy ra',
                    text: error.error,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                }).then((result) => {
                }).catch(swal.noop);
            }
        );
    }

    goToPersonnelCreate() {
        this.router.navigate(['/danh-sach-nhan-su/them-nhan-su']);
    }

    toggle() {
        $('.slide').slideToggle();
    }

    deletePersonnel() {
        swal({
            title: 'Xác nhận',
            text: "Bạn có muốn xóa bản ghi này",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            console.log(result)
            if (result) {
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        }).catch(swal.noop);
    }
}
