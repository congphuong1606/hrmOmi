import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd } from '@angular/router';
import { PersonnelService } from "../../services/personnel.service";
import { LoaderController } from "../../shared/loader/loader";
import { EmployeeExcelDepartment, Department, EmployeeExcelFile, EmployeeExcelJobStatus, JobStatus, EmployeeExcelPosition, Position } from "../../models/api/response/ListEmployeesResponse";
import { DataGlobalService } from "../../services/data.global.service";

declare var $: any;
declare var swal: any;

@Component({
    selector: 'import-personnel-cmp',
    moduleId: module.id,
    templateUrl: 'import-personnel.component.html'
})

export class ImportPersonnelComponent implements OnInit {
    form: FormGroup;
    uploadFileForm: FormGroup;
    isCheckAll: false;
    loaderController: LoaderController = new LoaderController();
    isValidDepartmentDefine = false;
    isOpenDepartmentDefine = true;
    isSubmitDepartmentDefine = false;

    isValidJobStatusDefine = false;
    isOpenJobStatusDefine = true;
    isSubmitJobStatusDefine = false;

    isValidPositionDefine = false;
    isOpenPositionDefine = true;
    isSubmitPositionDefine = false;
    token;

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private authService: AuthService,
        private dataGlobalService: DataGlobalService,
        private router: Router) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.createUploadFileForm();
        this.token = this.authService.getToken();
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/import')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.loaderController.enableLoader();
            this.getListDepartments();
            this.getListFiles();
            this.getListPositions();
            this.getListJobStatus();
        }
    }

    checkValidDepartmentDefine(isSubmit?) {
        this.isValidDepartmentDefine = true;
        if (isSubmit) {
            this.isSubmitDepartmentDefine = true;
            this.closeDepartmentDefine();
        }

        if (!this.employeeExcelDepartments.length) {
            this.isValidDepartmentDefine = false;
            this.isOpenDepartmentDefine = true;
            if (isSubmit) {
                this.isSubmitDepartmentDefine = false;
            }
            this.openDepartmentDefine();
        }
        this.employeeExcelDepartments.forEach((value, index) => {
            if (value.department === null || value.department.deleted_at !== null) {
                this.isValidDepartmentDefine = false;
                this.isOpenDepartmentDefine = true;
                if (isSubmit) {
                    this.isSubmitDepartmentDefine = false;
                }
                this.openDepartmentDefine();
            }
        });
    }

    onChangeDepartmentDefine(department) {
        this.checkValidDepartmentDefine();
    }

    openDepartmentDefine() {
        $('#department-define').slideDown(() => {
            this.isOpenDepartmentDefine = true;
        });
    }
    closeDepartmentDefine() {
        $('#department-define').slideUp(() => {
            this.isOpenDepartmentDefine = false;
        });
    }

    listDepartments: Department[] = [];
    getListDepartments() {
        this.loaderController.pushLoader();
        this.personnelService.getListDeparments().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listDepartments = data.departments;
                this.getListEmployeeExcelDepartments();
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    employeeExcelDepartments: EmployeeExcelDepartment[] = [];
    getListEmployeeExcelDepartments() {
        this.loaderController.pushLoader();
        this.personnelService.getListEmployeeExcelDepartments().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.employeeExcelDepartments = data.departments;
                this.employeeExcelDepartments.forEach((element, index) => {
                    this.listDepartments.forEach((e, i) => {
                        if (element.department !== null && element.department.id === e.id) {
                            this.employeeExcelDepartments[index].department = this.listDepartments[i];
                        }
                    });
                });
                this.checkValidDepartmentDefine(true);
                console.log(this.isValidDepartmentDefine);
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    applyDepartment() {
        const departments = [];
        this.employeeExcelDepartments.forEach(element => {
            departments.push({
                id: element.id,
                department_id: element.department.id
            });
        });
        this.loaderController.pushLoader();
        this.personnelService.applyEmployeeExcelDepartment(departments).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.checkValidDepartmentDefine(true);
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }
    ///////////////////////////////
    checkValidJobStatusDefine(isSubmit?) {
        this.isValidJobStatusDefine = true;
        if (isSubmit) {
            this.isSubmitJobStatusDefine = true;
            this.closeJobStatusDefine();
        }

        if (!this.employeeExcelJobStatus.length) {
            this.isValidJobStatusDefine = false;
            this.isOpenJobStatusDefine = true;
            if (isSubmit) {
                this.isSubmitJobStatusDefine = false;
            }
            this.openJobStatusDefine();
        }
        this.employeeExcelJobStatus.forEach((value, index) => {
            if (value.job_status === null || value.job_status.deleted_at !== null) {
                this.isValidJobStatusDefine = false;
                this.isOpenJobStatusDefine = true;
                if (isSubmit) {
                    this.isSubmitJobStatusDefine = false;
                }
                this.openJobStatusDefine();
            }
        });
    }

    onChangeJobStatusDefine(department) {
        this.checkValidJobStatusDefine();
    }

    openJobStatusDefine() {
        $('#job_status-define').slideDown(() => {
            this.isOpenJobStatusDefine = true;
        });
    }
    closeJobStatusDefine() {
        $('#job_status-define').slideUp(() => {
            this.isOpenJobStatusDefine = false;
        });
    }

    listJobStatus: JobStatus[] = [];
    getListJobStatus() {
        this.loaderController.pushLoader();
        this.personnelService.getListJobStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listJobStatus = data.jobs_status;
                this.getListEmployeeExcelJobStatus();
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }
    employeeExcelJobStatus: EmployeeExcelJobStatus[] = [];
    getListEmployeeExcelJobStatus() {
        this.loaderController.pushLoader();
        this.personnelService.getListEmployeeExcelJobStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.employeeExcelJobStatus = data.job_status;
                this.employeeExcelJobStatus.forEach((element, index) => {
                    this.listJobStatus.forEach((e, i) => {
                        if (element.job_status !== null && element.job_status.id === e.id) {
                            this.employeeExcelJobStatus[index].job_status = this.listJobStatus[i];
                        }
                    });
                });
                this.checkValidJobStatusDefine(true);
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    applyJobStatus() {
        const jobStatus = [];
        this.employeeExcelJobStatus.forEach(element => {
            jobStatus.push({
                id: element.id,
                job_status_id: element.job_status.id
            });
        });
        this.loaderController.pushLoader();
        this.personnelService.applyEmployeeExcelJobStatus(jobStatus).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.checkValidJobStatusDefine(true);
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }
    //////////////////////////////


    ///////////////////////////////
    checkValidPositionDefine(isSubmit?) {
        this.isValidPositionDefine = true;
        if (isSubmit) {
            this.isSubmitPositionDefine = true;
            this.closePositionDefine();
        }

        if (!this.employeeExcelPositions.length) {
            this.isValidPositionDefine = false;
            this.isOpenPositionDefine = true;
            if (isSubmit) {
                this.isSubmitPositionDefine = false;
            }
            this.openPositionDefine();
        }
        this.employeeExcelPositions.forEach((value, index) => {
            if (value.position === null || value.position.deleted_at !== null) {
                this.isValidPositionDefine = false;
                this.isOpenPositionDefine = true;
                if (isSubmit) {
                    this.isSubmitPositionDefine = false;
                }
                this.openPositionDefine();
            }
        });
    }

    onChangePositionDefine(department) {
        this.checkValidPositionDefine();
    }

    openPositionDefine() {
        $('#position-define').slideDown(() => {
            this.isOpenPositionDefine = true;
        });
    }
    closePositionDefine() {
        $('#position-define').slideUp(() => {
            this.isOpenPositionDefine = false;
        });
    }

    listPositions: Position[] = [];
    getListPositions() {
        this.loaderController.pushLoader();
        this.personnelService.getListPositions().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listPositions = data.positions;
                this.getListEmployeeExcelPosition();
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }
    employeeExcelPositions: EmployeeExcelPosition[] = [];
    getListEmployeeExcelPosition() {
        this.loaderController.pushLoader();
        this.personnelService.getListEmployeeExcelPositions().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.employeeExcelPositions = data.positions;
                this.employeeExcelPositions.forEach((element, index) => {
                    this.listPositions.forEach((e, i) => {
                        if (element.position !== null && element.position.id === e.id) {
                            this.employeeExcelPositions[index].position = this.listPositions[i];
                        }
                    });
                });
                this.checkValidPositionDefine(true);
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    applyPosition() {
        const positions = [];
        this.employeeExcelPositions.forEach(element => {
            positions.push({
                id: element.id,
                position_id: element.position.id
            });
        });
        this.loaderController.pushLoader();
        this.personnelService.applyEmployeeExcelPosition(positions).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.checkValidPositionDefine(true);
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }
    //////////////////////////////


    listFiles: EmployeeExcelFile[] = [];
    getListFiles() {
        this.loaderController.pushLoader();
        this.personnelService.getListEmployeeExcelFiles().subscribe(
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
        this.personnelService.uploadFilePersonnel(file).subscribe(
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

    ngAfterViewInit() {
        // Init Tooltips
        $('[rel="tooltip"]').tooltip();
    }
}
