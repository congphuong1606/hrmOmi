import { Component, OnInit, AfterViewInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, AbstractControl, FormControl, FormArray } from '@angular/forms';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { AuthService } from '../../services/authSevice';
import { PersonnelService } from '../../services/personnel.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Employee, JobStatus, WorkingStatus, Department, Position, LateReason } from '../../models/api/response/ListEmployeesResponse';
import { DataGlobalService } from '../../services/data.global.service';
import { CommonValidator } from '../../validation/common.validator';

declare var swal: any;
declare var $: any;
import * as moment from 'moment';
import { HelperFunction } from '../../functions';
import { LoaderController } from '../../shared/loader/loader';

@Component({
    selector: 'personnel-update-cmp',
    moduleId: module.id,
    templateUrl: 'personnel-update.component.html'
})

export class PersonnelUpdateComponent implements OnInit, AfterViewInit {

    form: FormGroup
    personnelForm: FormGroup
    id;
    avatarUrl = '';

    job_status: any;
    position: any;
    department: any;
    late_reason: any;

    status: any;
    listPage = [];
    working_status;

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

    employee: Employee;

    skillsString = '';

    initSkills = [];

    selectedSkills = [];

    showIt = false;
    isAdmin = false;
    token;

    loaderController: LoaderController = new LoaderController();

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router,
        private route: ActivatedRoute,
        private authService: AuthService) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.id = this.route.snapshot.paramMap.get('id');
        this.employee = new Employee();
        this.createForm();
        this.onChangeAvatar();
        this.isAdmin = this.dataGlobalService.isAdmin();
        this.token = this.authService.getToken();
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/sua-thong-tin-nhan-su')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.loaderController.enableLoader();
            this.getEmployee();
            this.getListDefine();
        }

    }

    ngAfterViewInit(): void {
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

    onCloseModalSkill(selected) {
        console.log(selected);
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

    onChangeAvatar(): void {
        this.personnelForm.get('avatar').valueChanges.subscribe(val => {
            console.log(val);
            if (val !== null && val.length) {
                const file = val[0];
                this.avatarUrl = window.URL.createObjectURL(file);
            }

        });
        this.personnelForm.get('employee_code').valueChanges.subscribe(val => {
            console.log(val);
        });
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
        this.personnelService.getListJobStatus().subscribe(
            data => {
                this.listJobStatus = data.jobs_status;
                this.listJobStatus.forEach((value, index) => {
                    if (this.employee.job_status !== null && this.employee.job_status.id === value.id) {
                        this.job_status = this.listJobStatus[index];
                    }
                });
            },
            error => {
                console.log(error);
            }
        );
    }

    getListWorkingStatus() {
        this.listWorkingStatus = [];
        this.personnelService.getListWorkingStatus().subscribe(
            data => {
                this.listWorkingStatus = data.working_status;
                this.listWorkingStatus.forEach((value, index) => {
                    if (this.employee.working_status_id === value.id) {
                        this.working_status = this.listWorkingStatus[index];
                    }
                });

            },
            error => {
                console.log(error);
            }
        );
    }

    getListPositions() {
        this.listPositions = [];
        this.personnelService.getListPositions().subscribe(
            data => {
                this.listPositions = data.positions;
                this.listPositions.forEach((value, index) => {
                    if (this.employee.position_id === value.id) {
                        this.position = this.listPositions[index];
                    }
                });
            },
            error => {
                console.log(error);
            }
        );
    }

    getListDepartments() {
        this.listDepartments = [];
        this.personnelService.getListDeparments().subscribe(
            data => {
                this.listDepartments = data.departments;
                this.listDepartments.forEach((value, index) => {
                    if (this.employee.department_id === value.id) {
                        this.department = this.listDepartments[index];
                    }
                });
            },
            error => {
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
                this.listLateReasons.forEach((value, index) => {
                    if (this.employee.late_reason_id === value.id) {
                        this.late_reason = this.listLateReasons[index];
                    }
                });
                this.employee.employee_late_reasons.forEach((value, index) => {
                    this.listLateReasons.forEach((e, k) => {
                        if (e.id === value.late_reason_id) {
                            this.late_reasons.push(this.fb.group({
                                start_date: [value.start_date !== null ? moment(value.start_date, 'YYYY-MM-DD').format('DD-MM-YYYY') : ''],
                                end_date: [value.end_date !== null ? moment(value.end_date, 'YYYY-MM-DD').format('DD-MM-YYYY') : ''],
                                late_reason: [this.listLateReasons[k]]
                            }));
                        }
                    });
                });
            },
            error => {
                console.log(error);
            }
        );
    }

    getEmployee() {
        this.personnelService.getEmployee(this.id).subscribe(
            data => {
                this.employee = data.employee;
                this.personnelForm.get('email').setAsyncValidators(CommonValidator.isEmailExistUpdate(this.personnelService, this.employee.id));
                this.personnelForm.get('employee_code').setAsyncValidators(CommonValidator.isEmployeeCodeExistUpdate(this.personnelService, this.employee.id));
                this.initSkills = this.employee.specialized_skills;
                const name = [];
                this.initSkills.forEach(element => {
                    name.push(element.name);
                });
                this.skillsString = name.join(', ');
                this.personnelForm.get('email').setValue(this.employee.email);
                this.personnelForm.get('employee_code').setValue(this.employee.employee_code);
                if (this.employee.birth_day !== null) {
                    this.personnelForm.get('birth_day').setValue(moment(this.employee.birth_day, 'YYYY-MM-DD').format('DD-MM-YYYY'));
                }
                if (this.employee.identification_date !== null) {
                    this.personnelForm.get('identification_date')
                        .setValue(moment(this.employee.identification_date, 'YYYY-MM-DD').format('DD-MM-YYYY'));
                }
                this.personnelForm.get('update_date')
                        .setValue(moment().format('DD-MM-YYYY'));
                if (this.employee.check_in_date !== null) {
                    this.personnelForm.get('check_in_date')
                        .setValue(moment(this.employee.check_in_date, 'YYYY-MM-DD').format('DD-MM-YYYY'));
                }
                if (this.employee.training_date !== null) {
                    this.personnelForm.get('training_date')
                        .setValue(moment(this.employee.training_date, 'YYYY-MM-DD').format('DD-MM-YYYY'));
                }
                if (this.employee.official_date !== null) {
                    this.personnelForm.get('official_date')
                        .setValue(moment(this.employee.official_date, 'YYYY-MM-DD').format('DD-MM-YYYY'));
                }
                this.personnelForm.get('full_name').setValue(this.employee.full_name);
                this.personnelForm.get('identification_number').setValue(this.employee.identification_number);
                this.personnelForm.get('identification_place_of').setValue(this.employee.identification_place_of);
                this.personnelForm.get('tax_code').setValue(this.employee.tax_code);
                this.personnelForm.get('permanent_address').setValue(this.employee.permanent_address);
                this.personnelForm.get('temporary_address').setValue(this.employee.temporary_address);
                this.personnelForm.get('bank_number').setValue(this.employee.bank_number);
                this.personnelForm.get('bank_name').setValue(this.employee.bank_name);
                this.personnelForm.get('bank_user_name').setValue(this.employee.bank_user_name);
                this.personnelForm.get('bank_branch').setValue(this.employee.bank_branch);
                this.personnelForm.get('phone_number').setValue(this.employee.phone_number);
                this.personnelForm.get('chatwork_account').setValue(this.employee.chatwork_account);
                this.personnelForm.get('personal_email').setValue(this.employee.personal_email);
                this.personnelForm.get('skype_account').setValue(this.employee.skype_account);
                this.personnelForm.get('facebook_link').setValue(this.employee.facebook_link);
                this.personnelForm.get('contact_user').setValue(this.employee.contact_user);
                this.avatarUrl = this.employee.avatar;
                this.listJobStatus.forEach((value, index) => {
                    if (this.employee.job_status !== null && this.employee.job_status.id === value.id) {
                        this.job_status = this.listJobStatus[index];
                    }
                });
                this.listWorkingStatus.forEach((value, index) => {
                    if (this.employee.working_status_id === value.id) {
                        this.working_status = this.listWorkingStatus[index];
                    }
                });
                this.listPositions.forEach((value, index) => {
                    if (this.employee.position_id === value.id) {
                        this.position = this.listPositions[index];
                    }
                });
                this.listDepartments.forEach((value, index) => {
                    if (this.employee.department_id === value.id) {
                        this.department = this.listDepartments[index];
                    }
                });
                this.listGenders.forEach((value, index) => {
                    if (this.employee.gender === value.id) {
                        this.gender = this.listGenders[index];
                    }
                });
                let check = false;
                this.listLateReasons.forEach((value, index) => {
                    if (this.employee.late_reason_id === value.id) {
                        this.late_reason = this.listLateReasons[index];
                        check = true;
                    }
                });
                this.employee.employee_late_reasons.forEach((value, index) => {
                    this.listLateReasons.forEach((e, k) => {
                        if (e.id === value.late_reason_id) {
                            this.late_reasons.push(this.fb.group({
                                start_date: [value.start_date !== null ? moment(value.start_date, 'YYYY-MM-DD').format('DD-MM-YYYY') : ''],
                                end_date: [value.end_date !== null ? moment(value.end_date, 'YYYY-MM-DD').format('DD-MM-YYYY') : ''],
                                late_reason: [this.listLateReasons[k]]
                            }));
                        }
                    });
                });
            },
            error => {

            }
        );
    }

    goBack() {
        window.history.back();
    }

    createForm() {
        this.personnelForm = this.fb.group({
            employee_code: ['', [Validators.required]],
            email: ['', [Validators.required, Validators.email]],
            birth_day: [''],
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
            avatar: [''],
            update_date: [''],
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
            ]),
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
    removeCurrentAttachFiles(file) {
        const index = this.employee.attach_files.indexOf(file);
        this.employee.attach_files[index].deleted = true;
    }
    restoreCurrentAttachFiles(file) {
        const index = this.employee.attach_files.indexOf(file);
        this.employee.attach_files[index].deleted = false;
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
            avatar: this.personnelForm.get('avatar').value === null ||
                this.personnelForm.get('avatar').value.length === 0 ? '' : this.personnelForm.get('avatar').value[0],
            late_reason_id: this.late_reason.id,
            skills: skills,
            attach_files: $('#attach-file')[0].files,
            contact_user: this.personnelForm.get('contact_user').value,
        };
        data.current_attach_files = this.employee.attach_files;
        if (this.department) {
            data.department_id = this.department.id;
        }
        if (this.position) {
            data.position_id = this.position.id;
        }
        if (this.job_status) {
            data.job_status_id = this.job_status.id;
        }
        if (this.working_status) {
            data.working_status_id = this.working_status.id;
        }
        if (this.gender) {
            data.gender = this.gender.id;
        }
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
        console.log(data);
        this.personnelService.updateEmployee(this.id, data).subscribe(
            () => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Thành công',
                    text: 'Đã cập nhật thành công nhân sự ' + this.personnelForm.get('full_name').value,
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
}
