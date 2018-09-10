import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd } from '@angular/router';
import { PersonnelService } from "../../services/personnel.service";
import { LoaderController } from "../../shared/loader/loader";
import { EmployeeExcelDepartment, Department, EmployeeExcelFile, EmployeeExcelJobStatus, JobStatus, EmployeeExcelPosition, Position, TimeKeepingExcelFile } from "../../models/api/response/ListEmployeesResponse";
import { DataGlobalService } from "../../services/data.global.service";

declare var $: any;
declare var swal: any;

@Component({
    selector: 'import-timekeeping-cmp',
    moduleId: module.id,
    templateUrl: 'import-timekeeping.component.html'
})

export class ImportTimeKeepingComponent implements OnInit {
    form: FormGroup;
    uploadFileForm: FormGroup;
    isCheckAll: false;
    loaderController: LoaderController = new LoaderController();
    token;

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private authService: AuthService,
        private globalDataService: DataGlobalService,
        private router: Router) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.createUploadFileForm();
        this.token = this.authService.getToken();
    }

    ngOnInit() {
        if (!this.globalDataService.checkPemisson('/cham-cong/duyet-du-lieu')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.loaderController.enableLoader();
            this.getListFiles();
        }

    }

    listFiles: TimeKeepingExcelFile[] = [];
    getListFiles() {
        this.loaderController.pushLoader();
        this.personnelService.getListTimeKeepingExcelFiles().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listFiles = data.files;
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    downloadExcelFile() {

    }

    checkAll() {
        if (this.isCheckAll) {
            this.employees.forEach((value, index) => {
                this.employees[index].is_accepted = 1;
            });
        } else {
            this.employees.forEach((value, index) => {
                this.employees[index].is_accepted = 0;
            });
        }
        this.setListChecked();
    }

    checkOne() {
        this.setListChecked();
    }
    listChecked = [];
    setListChecked() {
        this.listChecked = [];
        this.employees.forEach((value, index) => {
            if (value.is_accepted) {
                this.listChecked.push(value.id);
            }
        });
    }

    createUploadFileForm() {
        this.uploadFileForm = this.fb.group({
            file: [''],
        });
    }
    employees = null;
    uploadFile() {
        this.loaderController.pushLoader();
        const file = {
            file: this.uploadFileForm.get('file').value === null || this.uploadFileForm.get('file').value.length === 0 ? '' : this.uploadFileForm.get('file').value[0],
        };
        this.personnelService.uploadFileTimeKeeping(file).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listFiles = data.files;
                window.location.href = window.location.href.split('#')[0] + '#list-files';
            },
            error => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Thất bại',
                    text: error.error,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                });
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
