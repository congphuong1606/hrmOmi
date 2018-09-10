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

@Component({
    selector: 'update-project-manager-modal-cmp',
    moduleId: module.id,
    templateUrl: 'update-project-manager-modal.component.html',
    styles: [`
        .modal-body {
            padding: 0!important;
        }
        .ngx-datatable.material {
            box-shadow: none;
        }
    `]
})


export class UpdateProjectManagerModalComponent implements OnInit, OnChanges, AfterViewInit, OnDestroy {

    loaderController: LoaderController = new LoaderController();
    @ViewChild('modal') modal: ElementRef;
    @Input('show') show: boolean;
    @Input('currentSelected') currentSelected: any;
    @Output() close: EventEmitter<any> = new EventEmitter<any>();
    selected: any = null;
    projectManagers: any;
    selectedProjectManagers: any = [];
    constructor(private elementRef: ElementRef, private rd: Renderer2, private http: HttpClient,
        private fb: FormBuilder,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router, ) {

    }

    ngOnInit() {
        this.loaderController.enableLoader();
        this.getListProjectManagers();
    }

    ngOnDestroy(): void {
        this.loaderController.cancelLoader();
    }

    ngAfterViewInit() {
        // Init Tooltips
        $('[rel="tooltip"]').tooltip();
    }

    getListProjectManagers() {
        this.loaderController.pushLoader();
        this.personnelService.getListProjectManagers().subscribe(
            (data) => {
                this.loaderController.releaseLoader();
                this.projectManagers = data.project_managers;
                this.projectManagers.forEach((element, key) => {
                    this.currentSelected.project_managers.forEach(e => {
                        this.selected = this.projectManagers[key];
                    });
                    this.projectManagers[key].searched = true;
                });
            },
            (error: HttpErrorResponse) => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    searchText = '';
    search() {
        if (this.searchText !== '') {
            this.projectManagers.forEach((element, key) => {
                this.projectManagers[key].searched = element.full_name.search(new RegExp(this.searchText, 'i')) !== -1;
            });
        } else {
            this.projectManagers.forEach((element, key) => {
                this.projectManagers[key].searched = true;
            });
        }
    }

    unselectProjectManager() {
        this.selected = null;
    }

    updateSelected = null;
    submit() {
        this.loaderController.pushLoader();
        const project_manager_ids = [];
        if (this.selected !== null) {
            project_manager_ids.push(this.selected.id);
        } else {
            project_manager_ids.push(0);
        }
        const params = {
            project_manager_ids: project_manager_ids
        };
        this.personnelService.updateProjectManager(this.currentSelected.id, params).subscribe(
            (data) => {
                this.loaderController.releaseLoader();
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
