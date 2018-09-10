import { Component, OnInit, AfterViewInit, ElementRef, ViewChild, Input, Output, OnChanges, SimpleChanges, Renderer2, EventEmitter } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { log } from 'util';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { AuthService } from '../../services/authSevice';
import { Employee, Pagination, JobStatus, WorkingStatus } from '../../models/api/response/ListEmployeesResponse';
import { SearchEmployeeFormRequest, Department, Position } from '../../models/api/request/ListEmployeesRequest';
import { PersonnelService } from '../../services/personnel.service';
import { Router } from '@angular/router';
import { FileValidator } from '../../validation/file-validator';
import { LoaderController } from '../../shared/loader/loader';
import { DataGlobalService } from '../../services/data.global.service';
import { CommonValidator } from '../../validation/common.validator';
declare var swal: any;
declare var $: any;

@Component({
    selector: 'update-checkin-checkout-modal-cmp',
    moduleId: module.id,
    templateUrl: 'update-checkin-checkout-modal.component.html',
    styles: [`
        .modal-body {
            padding: 0!important;
        }
        .ngx-datatable.material {
            box-shadow: none;
        }
    `]
})


export class UpdateCheckinCheckoutModalComponent implements OnInit, OnChanges {

    loaderController: LoaderController = new LoaderController();
    @ViewChild('modal') modal: ElementRef;
    @Input('show') show: boolean;
    @Input('currentSelected') currentSelected: any;
    @Output() close: EventEmitter<any> = new EventEmitter<any>();
    checkInCheckOutForm: FormGroup;
    checkInCheckOut: any;
    constructor(private elementRef: ElementRef, private rd: Renderer2, private http: HttpClient,
        private fb: FormBuilder,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router, ) {

    }

    ngOnInit() {

        this.createForm();
        this.checkInCheckOut = this.currentSelected;
        this.checkInCheckOutForm.get('check_in').setValue(this.checkInCheckOut.check_in);
        this.checkInCheckOutForm.get('check_out').setValue(this.checkInCheckOut.check_out);
        // this.checkInCheckOutForm.get('ot').setValue(this.checkInCheckOut.tc);
    }

    createForm() {
        this.checkInCheckOutForm = this.fb.group({
            check_in: [''],
            check_out: [''],
            // ot: ['', Validators.compose([Validators.required, CommonValidator.isNumberFrom0, Validators.maxLength(3)])],
        });
    }
    updateSelected = null;


    reset() {
        this.checkInCheckOutForm.reset();
    }

    submit() {
        const data = {
            check_in: this.checkInCheckOutForm.get('check_in').value,
            check_out: this.checkInCheckOutForm.get('check_out').value,
            // ot: this.checkInCheckOutForm.get('ot').value,
        };
        this.personnelService.updateCheckInCheckOut(this.checkInCheckOut.id, data).subscribe(
            (data) => {
                this.updateSelected = data.time_on;
                swal({
                    title: 'Thành công',
                    text: 'Cập nhật thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Đóng',
                }).then((result) => {
                    this.closeModal();
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

    ngOnChanges(changes: SimpleChanges) {
        if (changes.show && changes.show.currentValue) {
            $(this.modal.nativeElement).modal('show');
        } else {
            $(this.modal.nativeElement).modal('hide');
        }
    }

    closeModal() {
        this.close.emit(this.updateSelected);
    }
}
