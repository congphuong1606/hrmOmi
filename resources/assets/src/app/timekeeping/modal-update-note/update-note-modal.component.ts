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
    selector: 'update-note-modal-cmp',
    moduleId: module.id,
    templateUrl: 'update-note-modal.component.html',
    styles: [`
        .modal-body {
            padding: 0!important;
        }
        .ngx-datatable.material {
            box-shadow: none;
        }
    `]
})


export class UpdateNoteModalComponent implements OnInit, OnChanges, AfterViewInit, OnDestroy {
    updateForm: FormGroup
    loaderController: LoaderController = new LoaderController();
    @ViewChild('modal') modal: ElementRef;
    @Input('show') show: boolean;
    @Input('currentSelected') currentSelected: any;
    @Output() close: EventEmitter<any> = new EventEmitter<any>();
    selected: any = null;
    accumulated: any;
    selectedProjectManagers: any = [];


    constructor(private elementRef: ElementRef, private rd: Renderer2, private http: HttpClient,
        private fb: FormBuilder,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router, ) {
        this.createForm();

    }


    ngOnInit() {
        this.loaderController.enableLoader();
        this.updateForm.get('note').setValue(this.currentSelected !== null ? this.currentSelected.note : '');
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
            note: [''],
        });
    }

    updateSelected = null;
    submit() {
        this.loaderController.pushLoader();
        const params = {
            note: this.updateForm.get('note').value
        };
        this.personnelService.addNote(this.currentSelected.id, params).subscribe(
            (data) => {
                this.loaderController.releaseLoader();
                this.updateSelected = Object.assign({}, this.currentSelected);
                this.updateSelected.note = data.note;

                swal({
                    title: 'Thành công',
                    text: 'Cập nhật ghi chú thành công',
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
