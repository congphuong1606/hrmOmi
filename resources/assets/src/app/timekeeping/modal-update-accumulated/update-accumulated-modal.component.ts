import { Component, OnInit, AfterViewInit, ElementRef, ViewChild, Input, Output, OnChanges, SimpleChanges, Renderer2, EventEmitter, OnDestroy } from '@angular/core';
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
import * as moment from 'moment';

@Component({
    selector: 'update-accumulated-modal-cmp',
    moduleId: module.id,
    templateUrl: 'update-accumulated-modal.component.html',
    styles: [`
        .modal-body {
            padding: 0!important;
        }
        .ngx-datatable.material {
            box-shadow: none;
        }
    `]
})


export class UpdateAccumulatedModalComponent implements OnInit, OnChanges, AfterViewInit, OnDestroy {
    updateForm: FormGroup
    loaderController: LoaderController = new LoaderController();
    @ViewChild('modal') modal: ElementRef;
    @Input('show') show: boolean;
    @Input('currentSelected') currentSelected: any;
    @Output() close: EventEmitter<any> = new EventEmitter<any>();
    selected: any = null;
    accumulated: any;
    selectedProjectManagers: any = [];

    manualTypes = [
        {
            id: 1,
            name: 'Phép'
        },
        {
            id: 2,
            name: 'OT'
        }
    ];

    months = [
        {
            id: 1,
            name: '1'
        },
        {
            id: 2,
            name: '2'
        },
        {
            id: 3,
            name: '3'
        },
        {
            id: 4,
            name: '4'
        },
        {
            id: 5,
            name: '5'
        },
        {
            id: 6,
            name: '6'
        },
        {
            id: 7,
            name: '7'
        },
        {
            id: 8,
            name: '8'
        },
        {
            id: 9,
            name: '9'
        },
        {
            id: 10,
            name: '10'
        },
        {
            id: 11,
            name: '11'
        },
        {
            id: 12,
            name: '12'
        },
    ];

    month: any;

    years = [
    ];
    year: any;

    manualType: any;

    constructor(private elementRef: ElementRef, private rd: Renderer2, private http: HttpClient,
        private fb: FormBuilder,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router, ) {
        this.createForm();
        this.manualType = this.manualTypes[0];
        this.month = this.months[0];
        const currentYear = moment().year();
        const yearCount = 6;
        for (let i = 0; i < yearCount; i++) {
            this.years.push({
                id: currentYear - i,
                name: '' + (currentYear - i),
            });
        }
        this.year = this.years[0];
    }


    ngOnInit() {
        this.loaderController.enableLoader();
        console.log(this.currentSelected);
    }

    ngOnDestroy(): void {
        this.loaderController.cancelLoader();
    }

    ngAfterViewInit() {
        // Init Tooltips
        $('[rel="tooltip"]').tooltip();
    }

    createForm() {
        this.updateForm = this.fb.group({
            time: ['', [Validators.required]],
            manual_type: [''],
            reason: [''],
            month: [''],
            year: [''],
        });
    }

    addDayoffAccumulated() {
        this.loaderController.pushLoader();
        this.personnelService.getListProjectManagers().subscribe(
            (data) => {
                this.loaderController.releaseLoader();
                this.accumulated = data.accumulated;
            },
            (error: HttpErrorResponse) => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    deleteAddition(added) {
        this.loaderController.pushLoader();
        this.personnelService.removeAcculatedAddition(added.id).subscribe(
            (data) => {
                this.loaderController.releaseLoader();
                this.updateSelected = data.accumulated;
                this.currentSelected = data.accumulated;
                swal({
                    title: 'Thành công',
                    text: 'Xóa thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Đóng',
                }).then((result) => {
                    this.closeModal();
                }).catch(swal.noop);
            },
            (error: HttpErrorResponse) => {
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

    updateSelected = null;
    submit() {
        this.loaderController.pushLoader();
        const params = {
            time: this.updateForm.get('time').value,
            manual_type: this.manualType.id,
            reason: this.updateForm.get('reason').value,
            month: this.updateForm.get('month').value.id,
            year: this.updateForm.get('year').value.id
        };
        this.personnelService.addAcculated(this.currentSelected.id, params).subscribe(
            (data) => {
                this.loaderController.releaseLoader();
                this.updateSelected = data.accumulated;
                this.currentSelected = data.accumulated;
                swal({
                    title: 'Thành công',
                    text: 'Bổ sung thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Đóng',
                }).then((result) => {
                    this.closeModal();
                }).catch(swal.noop);
            },
            (error: HttpErrorResponse) => {
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
