import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl, Validators } from "@angular/forms";
import { log } from "util";
import { HttpClient, HttpErrorResponse } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd, ActivatedRoute } from '@angular/router';
import { PersonnelService } from "../../services/personnel.service";
import { LoaderController } from "../../shared/loader/loader";
import { EmployeeExcelDepartment, Department, EmployeeExcelFile, EmployeeExcelFileDetail, TimeKeepingExcelFileDetail } from "../../models/api/response/ListEmployeesResponse";
import { CommonValidator } from "../../validation/common.validator";

declare var $: any;
declare var swal: any;

@Component({
    selector: 'checkin-checkout-update-cmp',
    moduleId: module.id,
    templateUrl: 'checkin-checkout-update.component.html'
})

export class CheckInCheckOutUpdateComponent implements OnInit {
    checkInCheckOutForm: FormGroup;
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
        private router: Router) {
        this.token = this.authService.getToken();
    }

    ngOnInit() {
        this.id = this.route.snapshot.paramMap.get('id');
        this.loaderController.enableLoader();
        this.getCheckInCheckOut();
        this.createForm();
    }

    checkInCheckOut: any;
    file: TimeKeepingExcelFileDetail = new TimeKeepingExcelFileDetail();
    getCheckInCheckOut() {
        this.loaderController.pushLoader();
        this.personnelService.getCheckInCheckOut(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.checkInCheckOut = data.time_on;
                this.checkInCheckOutForm.get('check_in').setValue(this.checkInCheckOut.check_in);
                this.checkInCheckOutForm.get('check_out').setValue(this.checkInCheckOut.check_out);
                this.checkInCheckOutForm.get('ot').setValue(this.checkInCheckOut.tc);
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    createForm() {
        this.checkInCheckOutForm = this.fb.group({
            check_in: [''],
            check_out: [''],
            ot: ['', Validators.compose([Validators.required, CommonValidator.isNumberFrom0, Validators.maxLength(3)])],
        });
    }

    submit() {
        const data = {
            check_in: this.checkInCheckOutForm.get('check_in').value,
            check_out: this.checkInCheckOutForm.get('check_out').value,
            ot: this.checkInCheckOutForm.get('ot').value,
        };
        this.personnelService.updateCheckInCheckOut(this.id, data).subscribe(
            () => {
                swal({
                    title: 'Thành công',
                    text: 'Cập nhật thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Danh sách',
                }).then((result) => {
                    if (result) {
                        this.router.navigate(['./cham-cong/check-in-check-out']);
                    }
                }).catch(swal.noop);
            },
            (error: HttpErrorResponse) => {
                if (error.headers.get('content-type') === 'application/json') {
                    if (error.error !== null) {
                        const err = JSON.parse(error.error).error;
                        Object.keys(err).forEach((key) => {
                            this.checkInCheckOut.get(key).setErrors({ server: err[key] });
                        });
                    }
                }
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
