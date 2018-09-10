import { Component, OnInit, AfterViewInit } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators, AbstractControl, FormArray } from '@angular/forms';
import { log } from 'util';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { AuthService } from '../../services/authSevice';
import { Employee, Pagination, JobStatus, WorkingStatus, LateReason } from '../../models/api/response/ListEmployeesResponse';
import { SearchEmployeeFormRequest, Department, Position } from '../../models/api/request/ListEmployeesRequest';
import { PersonnelService } from '../../services/personnel.service';
import { Router } from '@angular/router';
import { FileValidator } from '../../validation/file-validator';
import { DataGlobalService } from '../../services/data.global.service';
import { CommonValidator } from '../../validation/common.validator';
import { LoaderController } from '../../shared/loader/loader';
declare var swal: any;
declare var $: any;
import * as moment from 'moment';
import { HelperFunction } from '../../functions';

@Component({
    selector: 'personnel-create-cmp',
    moduleId: module.id,
    templateUrl: 'personnel-create.component.html'
})

export class PersonnelCreateComponent implements OnInit, AfterViewInit {

    form: FormGroup
    personnelForm: FormGroup
    job_status: any;
    position: any;
    department: any;
    loaderController: LoaderController = new LoaderController();
    status: any;
    listPage = [];
    working_status;
    late_reason: any;

    listJobStatus: JobStatus[];
    listWorkingStatus: WorkingStatus[];
    listDepartments: Department[];
    listPositions: Position[];
    listLateReasons: LateReason[];
    listGenders = [
        {
            id: 0,
            name: 'Không xác định'
        },
        {
            id: 1,
            name: 'Nam'
        },
        {
            id: 2,
            name: 'Nữ'
        }
    ];
    gender: any;

    skillsString = '';

    selectedSkills = [];

    showIt = false;

    avatarUrl = '';


    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router,
        private authService: AuthService) {
        this.createForm();
        this.onChangeAvatar();
        this.gender = this.listGenders[0];

    }
    goBack() {
        window.history.back();
    }

    ngOnInit() {
        //  Init Bootstrap Select Picker
        if (!this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/them-nhan-su')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.loaderController.enableLoader();
            this.getListDefine();
        }
    }


    onCloseModalSkill(selected) {
        const name = [];
        this.selectedSkills = selected;
        if (selected) {
            selected.forEach(element => {
                name.push(element.name);
            });
            this.skillsString = name.join(', ');
        }
        this.showIt = false;
    }
    openModalSkill() {
        this.showIt = true;
    }


    showModal() {
        this.showIt = true;
    }
    closeModal(newName: string) {
        this.showIt = false;
    }

    getListDefine() {
        this.getListJobStatus();
        this.getListWorkingStatus();
        this.getListDepartments();
        this.getListPositions();
        this.getListLateReasons();

    }
    getListJobStatus() {
        this.listJobStatus = [];
        this.loaderController.pushLoader();
        this.personnelService.getListJobStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listJobStatus = data.jobs_status;
                if (this.listJobStatus.length) {
                    this.job_status = this.listJobStatus[0];
                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    getListWorkingStatus() {
        this.listWorkingStatus = [];
        this.loaderController.pushLoader();
        this.personnelService.getListWorkingStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listWorkingStatus = data.working_status;
                if (this.listWorkingStatus.length) {
                    this.working_status = this.listWorkingStatus[0];
                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    getListPositions() {
        this.listPositions = [];
        this.loaderController.pushLoader();
        this.personnelService.getListPositions().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listPositions = data.positions;
                if (this.listPositions.length) {
                    this.position = this.listPositions[0];

                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    getListDepartments() {
        this.listDepartments = [];
        this.loaderController.pushLoader();
        this.personnelService.getListDeparments().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listDepartments = data.departments;
                if (this.listDepartments.length) {
                    this.department = this.listDepartments[0];
                }
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    getListLateReasons() {
        this.listLateReasons = [];
        this.personnelService.getListLateReasons().subscribe(
            data => {
                this.listLateReasons = data.late_reasons;
                this.listLateReasons.unshift(new LateReason());
                this.late_reason = this.listLateReasons[0];
            },
            error => {
                console.log(error);
            }
        );
    }

    onChangeAvatar(): void {
        this.personnelForm.get('avatar').valueChanges.subscribe(val => {
            if (val !== null && val.length) {
                const file = val[0];
                this.avatarUrl = window.URL.createObjectURL(file);
            }

        });
    }

    createForm() {
        this.personnelForm = this.fb.group({
            employee_code: ['', [Validators.required], CommonValidator.isEmployeeCodeExistCreate(this.personnelService)],
            email: ['', [Validators.required, Validators.email], CommonValidator.isEmailExistCreate(this.personnelService)],
            birth_day: [''],
            password: ['', [Validators.required, CommonValidator.isValidPassword]],
            full_name: ['', Validators.required],
            identification_number: ['', [CommonValidator.isNumberFrom0Nullable]],
            identification_date: [''],
            identification_place_of: [''],
            tax_code: ['', [CommonValidator.isNumberFrom0Nullable]],
            permanent_address: [''],
            temporary_address: [''],
            bank_number: ['', [CommonValidator.isNumberFrom0Nullable]],
            bank_name: [''],
            bank_user_name: [''],
            bank_branch: [''],
            phone_number: ['', [Validators.required]],
            personal_email: ['', CommonValidator.emailNullable],
            chatwork_account: [''],
            skype_account: [''],
            facebook_link: [''],
            avatar: ['',],
            update_date: [moment().format('DD-MM-YYYY')],
            check_in_date: [''],
            training_date: [''],
            official_date: [''],
            department: [''],
            position: [''],
            late_reason: [''],
            job_status: [''],
            working_status: [''],
            skills: [''],
            gender: [''],
            contact_user: [''],
            late_reasons: this.fb.array([
            ])
        });
    }

    get late_reasons() {
        return this.personnelForm.get('late_reasons') as FormArray;
    }
    addLateReasons() {
        this.late_reasons.push(this.fb.group({
            start_date: [''],
            end_date: [''],
            late_reason: [this.listLateReasons.length ? this.listLateReasons[0] : ''],
        }));
    }

    removeLateReasons(i) {
        this.late_reasons.removeAt(i);
    }

    submit() {
        this.loaderController.pushLoader();
        let skills;
        skills = [];
        this.selectedSkills.forEach(element => {
            skills.push(element.id);
        });
        skills = skills.join(',');
        const data: any = {
            employee_code: this.personnelForm.get('employee_code').value,
            email: this.personnelForm.get('email').value,
            full_name: this.personnelForm.get('full_name').value,
            birth_day: this.personnelForm.get('birth_day').value,
            password: this.personnelForm.get('password').value,
            identification_number: this.personnelForm.get('identification_number').value,
            identification_date: this.personnelForm.get('identification_date').value,
            identification_place_of: this.personnelForm.get('identification_place_of').value,
            tax_code: this.personnelForm.get('tax_code').value,
            permanent_address: this.personnelForm.get('permanent_address').value,
            temporary_address: this.personnelForm.get('temporary_address').value,
            bank_number: this.personnelForm.get('bank_number').value,
            bank_name: this.personnelForm.get('bank_name').value,
            bank_user_name: this.personnelForm.get('bank_user_name').value,
            bank_branch: this.personnelForm.get('bank_branch').value,
            phone_number: this.personnelForm.get('phone_number').value,
            chatwork_account: this.personnelForm.get('chatwork_account').value,
            personal_email: this.personnelForm.get('personal_email').value,
            skype_account: this.personnelForm.get('skype_account').value,
            facebook_link: this.personnelForm.get('facebook_link').value,
            update_date: this.personnelForm.get('update_date').value,
            check_in_date: this.personnelForm.get('check_in_date').value,
            training_date: this.personnelForm.get('training_date').value,
            official_date: this.personnelForm.get('official_date').value,
            avatar: this.personnelForm.get('avatar').value === null || this.personnelForm.get('avatar').value.length === 0 ? '' : this.personnelForm.get('avatar').value[0],
            department_id: this.department.id,
            position_id: this.position.id,
            job_status_id: this.job_status.id,
            working_status_id: this.working_status.id,
            late_reason_id: this.late_reason.id,
            skills: skills,
            gender: this.gender.id,
            attach_files: $('#attach-file')[0].files,
            contact_user: this.personnelForm.get('contact_user').value,
        };
        if (HelperFunction.required(data.birth_day)) {
            data.birth_day = moment(data.birth_day, 'DD-MM-YYYY').format('YYYY-MM-DD');
        }
        if (HelperFunction.required(data.identification_date)) {
            data.identification_date = moment(data.identification_date, 'DD-MM-YYYY').format('YYYY-MM-DD');
        }
        if (HelperFunction.required(data.update_date)) {
            data.update_date = moment(data.update_date, 'DD-MM-YYYY').format('YYYY-MM-DD');
        }
        if (HelperFunction.required(data.check_in_date)) {
            data.check_in_date = moment(data.check_in_date, 'DD-MM-YYYY').format('YYYY-MM-DD');
        }
        if (HelperFunction.required(data.training_date)) {
            data.training_date = moment(data.training_date, 'DD-MM-YYYY').format('YYYY-MM-DD');
        }
        if (HelperFunction.required(data.official_date)) {
            data.official_date = moment(data.official_date, 'DD-MM-YYYY').format('YYYY-MM-DD');
        }
        data.late_reasons = [];
        this.late_reasons.controls.forEach((v, k) => {
            data.late_reasons.push(
                {
                    start_date: HelperFunction.required(v.get('start_date').value) ? moment(v.get('start_date').value, 'DD-MM-YYYY').format('YYYY-MM-DD') : '',
                    end_date: HelperFunction.required(v.get('end_date').value) ? moment(v.get('end_date').value, 'DD-MM-YYYY').format('YYYY-MM-DD') : '',
                    late_reason_id: v.get('late_reason').value.id,
                }
            )
        });
        this.personnelService.addEmployee(data).subscribe(
            () => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Thành công',
                    text: 'Đã tạo thành công nhân sự ' + this.personnelForm.get('full_name').value,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Danh sách',
                }).then((result) => {
                    if (result) {
                        this.goBack();
                    }
                }).catch(swal.noop);
            },
            (error: HttpErrorResponse) => {
                this.loaderController.releaseLoader();
                if (error.headers.get('content-type') === 'application/json') {
                    if (error.error !== null) {
                        const err = JSON.parse(error.error).error;
                        Object.keys(err).forEach((key) => {
                            this.personnelForm.get(key).setErrors({ server: err[key] });
                        });
                    }
                }
                swal({
                    title: 'Đã có lỗi xảy ra',
                    text: 'Vui lòng kiểm tra lại',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                }).then((result) => {
                }).catch(swal.noop);
            }
        );
    }

    ngAfterViewInit(): void {
        if ($('.selectpicker').length !== 0) {
            $('.selectpicker').selectpicker({
                iconBase: 'ti',
                tickIcon: 'ti-check'
            });
        }
        $('.datepicker').datetimepicker({
            format: 'DD-MM-YYYY',    //use this format if you want the 12hours timpiecker with AM/PM toggle
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        });
    }

}
