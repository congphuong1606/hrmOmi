import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd, ActivatedRoute } from '@angular/router';
import { PersonnelService } from "../../services/personnel.service";
import { LoaderController } from "../../shared/loader/loader";
import { EmployeeExcelDepartment, Department, EmployeeExcelFile, EmployeeExcelFileDetail } from "../../models/api/response/ListEmployeesResponse";
import { DataGlobalService } from "../../services/data.global.service";

declare var $: any;
declare var swal: any;

@Component({
    selector: 'import-personnel-file-detail-cmp',
    moduleId: module.id,
    templateUrl: 'import-personnel-file-detail.component.html'
})

export class ImportPersonnelFileDetailComponent implements OnInit {
    form: FormGroup;
    uploadFileForm: FormGroup;
    isCheckAll: false;
    loaderController: LoaderController = new LoaderController();
    token;
    id;
    listChecked = [];

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
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
        this.getEmployeeExcelFile();
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

    checkOne(employee) {
        this.setListChecked();
    }

    setListChecked() {
        this.listChecked = [];
        this.file.data.forEach((value, index) => {
            if (value.is_checked && !value.is_duplicate && value.employee_id === null &&  !value.is_accepted) {
                this.listChecked.push(value.id);
            }
        });
    }

    listDepartments: Department[] = [];
    getListDepartments() {
        this.loaderController.pushLoader();
        this.personnelService.getListDeparments().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listDepartments = data.departments;
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }


    file: EmployeeExcelFileDetail = new EmployeeExcelFileDetail();
    getEmployeeExcelFile() {
        this.loaderController.pushLoader();
        this.personnelService.getEmployeeExcelFile(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.file = data.file;
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    parse() {
        this.loaderController.pushLoader();
        this.personnelService.parseEmployeeExcelFile(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.file = data.file;
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
        this.personnelService.applyEmployeeExcelFile(this.id, this.listChecked).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.file = data.file;
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

    login() {
        return this.authService.login(this.form.value);
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

    ngAfterViewInit() {
        // Init Tooltips
        $('[rel="tooltip"]').tooltip();
    }
}
