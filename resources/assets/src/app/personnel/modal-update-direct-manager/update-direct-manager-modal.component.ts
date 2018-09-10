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
    selector: 'update-direct-manager-modal-cmp',
    moduleId: module.id,
    templateUrl: 'update-direct-manager-modal.component.html',
    styles: [`
        .modal-body {
            padding: 0!important;
        }
        .ngx-datatable.material {
            box-shadow: none;
        }
    `]
})


export class UpdateDirectManagerModalComponent implements OnInit, OnChanges, AfterViewInit {

    loaderController: LoaderController = new LoaderController();
    @ViewChild('modal') modal: ElementRef;
    @Input('show') show: boolean;
    @Input('currentSelected') currentSelected: any;
    @Output() close: EventEmitter<any> = new EventEmitter<any>();
    selected: any = null;
    directManagers: any;
    selectedDirectManagers: any = [];
    constructor(private elementRef: ElementRef, private rd: Renderer2, private http: HttpClient,
        private fb: FormBuilder,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router, ) {

    }

    ngOnInit() {
        this.getListDirectManagers();
    }

    ngAfterViewInit() {
        // Init Tooltips
        $('[rel="tooltip"]').tooltip();
    }

    getListDirectManagers() {
        this.personnelService.getListDirectManagers().subscribe(
            (data) => {
                this.directManagers = data.direct_managers;
                this.directManagers.forEach((element, key) => {
                    if (this.currentSelected.direct_manager_id === element.id) {
                        this.selected = this.directManagers[key];
                    }
                    this.directManagers[key].searched = true;
                });
            },
            (error: HttpErrorResponse) => {
                console.log(error);
            }
        );
    }
    searchText = '';
    search() {
        if (this.searchText !== '') {
            this.directManagers.forEach((element, key) => {
                this.directManagers[key].searched = element.full_name.search(new RegExp(this.searchText, 'i')) !== -1;
            });
        } else {
            this.directManagers.forEach((element, key) => {
                this.directManagers[key].searched = true;
            });
        }
    }

    unselectDirectManager() {
        this.selected = null;
    }

    updateSelected = null;
    submit() {
        const params = {
            direct_manager_id: this.selected.id
        };
        this.personnelService.updateDirectManager(this.currentSelected.id, params).subscribe(
            (data) => {
                this.updateSelected = data.employee;
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
