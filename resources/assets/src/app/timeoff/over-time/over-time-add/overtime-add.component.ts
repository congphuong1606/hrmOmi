import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {ActivatedRoute, Router} from '@angular/router';
import {DataGlobalService} from '../../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';
import * as moment from 'moment';
import {OvertimeService} from '../../../services/overtime.service';
import {CategoryOtherReponse, CategoryReponse} from '../../../models/api/response/CategoriesReponse';
import {CategoryOtherService} from '../../../services/category/category-other.service';
import {UserCourseRepose} from '../../../models/api/response/CourseRepose';
import {CourseService} from '../../../services/course/course.service';
import {CategoryService} from '../../../services/category/category.service';
import {el} from '@angular/platform-browser/testing/src/browser_util';


declare var $: any;
declare var swal: any;

declare interface UserOT {
    id: number;
    name: string;
    email: string;
    work_content: string;
    start_datetime: string;
    end_datetime: string;
};

@Component({
    selector: 'overtime-add-cmp',
    moduleId: module.id,
    templateUrl: 'overtime-add.component.html'
})

export class AddOvertimeComponent implements OnInit, OnDestroy {
    errorMsg = '';
    numDate: number;
    listUserSelected: UserCourseRepose[] = [];
    subscription: Subscription;
    textDefault = 'Chọn dự án';
    departmentsName = 'Tất cả phòng ban';
    officePositionName = 'Tất cả chức danh';
    JobStatusName = 'Tất cả trạng thái';
    projects: CategoryOtherReponse[] = [];
    userSelecteds: UserOT[] = [];
    saveOverTimeloading = false;
    department_id = '';
    job_position_id = '';
    job_status_id = '';
    search_value = '';
    projectId: number;
    userIds: number[] = [];
    dataRows: UserCourseRepose[] = [];
    sub1: Subscription;
    sub2: Subscription;
    sub3: Subscription;
    sub4: Subscription;
    check1 = false;
    check2 = false;
    check3 = false;
    check4 = false;
    check5 = false;
    subscriptionDataRows: Subscription;
    jobStatuss: CategoryReponse[] = [];
    officePositions: CategoryReponse[] = [];
    departments: CategoryReponse[] = [];
    isLoadSuccess: boolean;
    approver = 'hr_admin@ominext.com';
    approverName = 'HCNS';
    focusOut = false;
    paramOvertimeId = '';
    sub7: Subscription;
    isDeleteOT = false;
    wCoppyStartTime = '';
    wCoppyEndTime = '';
    sub8: Subscription;
    private datalistEmail: any[] = [];


    ngOnDestroy(): void {
        this.subscriptionDataRows !== undefined ? this.subscriptionDataRows.unsubscribe() : console.log('');
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log('');
        this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
        this.sub2 !== undefined ? this.sub2.unsubscribe() : console.log('');
        this.sub3 !== undefined ? this.sub3.unsubscribe() : console.log('');
        this.sub4 !== undefined ? this.sub4.unsubscribe() : console.log('');
        this.sub7 !== undefined ? this.sub7.unsubscribe() : console.log('');
        this.sub8 !== undefined ? this.sub8.unsubscribe() : console.log('');
    }

    constructor(private router: Router,
                private fb: FormBuilder,
                private overtimeService: OvertimeService,
                private courseService: CourseService,
                private route: ActivatedRoute,
                private categoryService: CategoryService,
                private categoryOtherService: CategoryOtherService,
                public dataGlobalService: DataGlobalService) {
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-lam-them-gio')) {
            window.history.back();
        } else {
            this.getData();
            this.getPramRouter();
            this.numDate = Date.now();
        }

    }

    getPramRouter(): void {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.paramOvertimeId = queryParamMap.get('id') === null ? '' : queryParamMap.get('id');
        if (this.paramOvertimeId !== '') {
            this.getDataOverTime();
        }
    }

    getDataOverTime() {
        this.sub7 = this.overtimeService.get(this.paramOvertimeId).subscribe(
            reponse => {
                const data = reponse.over_time;
                this.setData({name: data.other_category.name, id: data.project_category_id});  // set tên và id dự án
                // this.approver = data.approver; // set email người duyệt
              //  this.focusOutFunction(); // kiểm tra thông tin nguoi duyet
                (data.details as any[]).forEach(item => {  // set data user
                    const userOt: UserOT = {
                        id: item.user.id,
                        name: item.user.name,
                        email: item.user.content,
                        start_datetime: this.convertDateTime(item.start_datetime),
                        end_datetime: this.convertDateTime(item.end_datetime),
                        work_content: item.content,
                    };
                    this.userSelecteds.push(userOt);
                });
            },
            error1 => {
                this.dataGlobalService.actionFail(error1.error);
            }
        );
    }

    convertDateTime(time: string): string {
        const ddmmyyyy = time.split(' ')[0];
        const hhmm = time.split(' ')[1];
        const date = ddmmyyyy.split('-')[2] + '-' + ddmmyyyy.split('-')[1] + '-' + ddmmyyyy.split('-')[0]
        return date + ' ' + hhmm.split(':')[0] + ':' + hhmm.split(':')[1];
    }

    selectItemEmployee(row: UserCourseRepose): void {
        row.is_selected = !row.is_selected;
        if (row.is_selected) {
            this.userIds.push(row.id);
        } else {
            this.userIds = this.userIds.filter(h => h !== row.id);
        }
    }


    initDatePicker(idDateTimePicker: string): void {
        $('#' + idDateTimePicker).datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            ignoreReadonly: true,
            sideBySide: true,
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
        }).attr('readonly', 'readonly');
    }


    saveUserSelected(): void {
        this.userSelecteds.forEach(element => {
            const id = this.userIds.find(userId => {
                return userId === element.id;
            });
            if (id === undefined) {
                this.userSelecteds = this.userSelecteds.filter(item => item.id !== element.id);
            }
        });
        this.userIds.forEach(userId => {
            const item = this.dataRows.find(obj => {
                return obj.id === userId;
            });
            if (item !== undefined) {
                const item1 = this.userSelecteds.find(obj => {
                    return obj.id === item.id;
                });
                if (item1 === undefined) {
                    const userOt: UserOT = {
                        id: item.id,
                        name: item.name,
                        email: item.email,
                        start_datetime: (moment().format('DD-MM-YYYY')) + ' 18:30',
                        end_datetime: (moment().format('DD-MM-YYYY')) + ' 19:00',
                        work_content: '',
                    }

                    this.userSelecteds.push(userOt);
                }
            }
        });

    }

    setDataOption(type: string, item: any): void {
        const id = item !== '' ? item.id + '' : '';
        if (type === 'departments') {
            this.departmentsName = item !== '' ? item.name : 'Tất cả phòng ban';
            this.department_id = id;
        }
        if (type === 'positions') {
            this.officePositionName = item !== '' ? item.name : 'Tất cả chức danh';
            this.job_position_id = id;
        }
        if (type === 'jobstatus') {
            this.JobStatusName = item !== '' ? item.name : 'Tất cả trạng thái';
            this.job_status_id = id;
        }
        this.isLoadSuccess = false;
        this.getListEmployees();
    }

    onSearchValueChange(searchValue: string) {
        this.search_value = searchValue.trim();
    }

    actionSearchValue(): void {
        this.isLoadSuccess = false;
        this.getListEmployees();
    }


    saveOverTime(): void {
        if (this.projectId !== undefined) {
            if (this.userSelecteds.length !== 0) {
                this.errorMsg = '';
                this.createOvertime();
            } else {
                this.errorMsg = 'Danh sách nhân viên làm ngoài giờ trống!';
            }
        } else {
            this.errorMsg = 'Dự án OT không thể trống!';
        }
    }


    createOvertime() {
        let data = {
            project_category_id: this.projectId,
            approver: this.approver,
            over_time_details: [],
        }
        this.userSelecteds.forEach(element => {
            const user = {
                user_id: element.id,
                content: element.work_content,
                start_datetime: $('#start_datetime_' + element.id).val().trim(),
                end_datetime: $('#end_datetime_' + element.id).val().trim(),
            };
            data.over_time_details.push(user);
        });
        if (this.validateWc(data.over_time_details)) {
            if (this.validateDateTimeOT(data.over_time_details)) {
                this.errorMsg = '';
                swal({
                    title: 'Gửi đơn đăng ký làm ngoài giờ ?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    preConfirm: function () {
                        return new Promise(resolve => {
                            setTimeout(function () {
                                resolve();
                            }, 500);
                        });
                    },
                }).then(r => {
                    this.dataGlobalService.disBtnSubmit();
                    if (this.paramOvertimeId === '') {
                        this.subscription = this.overtimeService.create(data).subscribe(
                            res => {
                                this.dataGlobalService.enableBtnSubmit();
                                if (res.status = 'success') {
                                    setTimeout(function () {
                                        swal({
                                            title: 'Thành công',
                                            text: 'Bạn đã gửi đơn đăng ký làm ngoài giờ!',
                                            type: 'success',
                                            confirmButtonText: 'Thoát',
                                            allowOutsideClick: false,
                                        }).then(isConfirm => {
                                            if (isConfirm) {
                                                window.history.back();
                                            }
                                        });
                                    }, 1000);
                                } else {
                                    swal(res.message);
                                }
                            },
                            error => {
                                this.dataGlobalService.enableBtnSubmit();
                                this.dataGlobalService.actionFail(error.error);
                            });
                    } else {
                        this.subscription = this.overtimeService.update(data, this.paramOvertimeId).subscribe(
                            res => {
                                this.dataGlobalService.enableBtnSubmit();
                                if (res.status = 'success') {
                                    setTimeout(function () {
                                        swal({
                                            title: 'Thành công',
                                            text: 'Bạn đã cập nhật đơn đăng ký làm ngoài giờ!',
                                            type: 'success',
                                            confirmButtonText: 'Thoát',
                                            allowOutsideClick: false,
                                        }).then(isConfirm => {
                                            if (isConfirm) {
                                                window.history.back();
                                            }
                                        }, f => {

                                        });
                                    }, 1000);
                                } else {
                                    swal(res.message);
                                }
                            },
                            error => {
                                this.dataGlobalService.enableBtnSubmit();
                                this.dataGlobalService.actionFail(error.error);
                            });
                    }
                }, cancel => {
                });
            } else {
                this.errorMsg = 'Thời gian làm ngoài giờ đang không đúng!';
            }
        } else {
            this.errorMsg = 'Công việc làm ngoài giờ của ai đó đang trống!';
        }


    }

    validateDateTimeOT(over_time_details: any[]) {
        let check = true;
        over_time_details.forEach(item => {
            if (item.start_datetime === '' || item.end_datetime === '') {
                check = false;
            } else {
                const time1 = moment(item.start_datetime, 'DD-MM-YYYY HH:mm');
                const time2 = moment(item.end_datetime, 'DD-MM-YYYY HH:mm');
                const duration1 = moment.duration(time2.diff(time1));
                if (duration1.asMinutes() <= this.dataGlobalService.getTimeConfig().in_late_threshold) {
                    check = false;
                }

            }
        });
        return check;
    }

    getData() {
        this.sub4 = this.categoryOtherService.getList('projects').subscribe(
            data => {
                this.projects = data.item_category as CategoryOtherReponse[];
                this.check4 = true;
                this.sub4 !== undefined ? this.sub4.unsubscribe() : console.log('');
            },
            error => {
                this.check4 = true;
                this.sub4 !== undefined ? this.sub4.unsubscribe() : console.log('');
                this.dataGlobalService.actionFail(error.message);
            }
        );
        this.sub1 = this.categoryService.getList('job_status').subscribe(
            repo => {
                this.check1 = true;
                this.jobStatuss = (repo.jobs_status as CategoryReponse[]);
                this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log(':D');
            },
            error => {
                this.check1 = true;
                this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log(':D');
                this.dataGlobalService.actionFail(error.error);
            });
        this.sub2 = this.categoryService.getList('positions').subscribe(
            repo => {
                this.check2 = true;
                this.officePositions = (repo.positions as CategoryReponse[]);
                this.sub2 !== undefined ? this.sub2.unsubscribe() : console.log(':D');
            },
            error => {
                this.check2 = true;
                this.sub2 !== undefined ? this.sub2.unsubscribe() : console.log(':D');
                this.dataGlobalService.actionFail(error.error);
            });
        this.sub3 = this.categoryService.getList('departments').subscribe(
            repo => {
                this.check3 = true;
                this.departments = (repo.departments as CategoryReponse[]);
                this.sub3 !== undefined ? this.sub3.unsubscribe() : console.log(':D');
            },
            error => {
                this.check3 = true;
                this.sub3 !== undefined ? this.sub3.unsubscribe() : console.log(':D');
                this.dataGlobalService.actionFail(error.error);
            });
        this.getListEmployees();

    }

    getListEmployees(): void {
        this.isLoadSuccess = false;
        this.dataRows.length = 0;
        this.subscriptionDataRows = this.courseService.searchUser(this.department_id,
            this.job_position_id, this.search_value, '').subscribe(
            repo => {
                this.check5 = true;
                this.dataRows = repo.users as UserCourseRepose[];
                this.isLoadSuccess = true;
            },
            error => {
                this.check5 = true;
                this.isLoadSuccess = true;
                this.dataGlobalService.actionFail(error.message);
            }
        );
    }


    setData(project: any): void {
        this.errorMsg = '';
        if (project !== '') {
            this.textDefault = project.name;
            this.projectId = project.id;
        } else {
            const item = {
                name: 'Các dự án',
                path: 'cac-du-an',
                type: 'projects',
            };
            this.router.navigate(['../danh-muc/danh-sach-trong-danh-muc-khac/', item.path], {
                queryParams: {
                    category_type: item.type,
                    category_name: item.name,
                }
            });
        }

    }


    onBack(): void {
        this.ngOnDestroy();
        window.history.back();
    }


    focusOutFunction() {
        this.dataGlobalService.checkEmailFocus(this.approver).subscribe(
            data => {
                console.log(data);
                this.focusOut = true;
                this.approver = data.user.email
                this.approverName = data.user.name;
            }, error => {
                console.log(error);
                this.approverName = '';
                this.focusOut = true;
            }
        );
    }

    focusFuntion() {
        this.errorMsg = '';
        this.focusOut = false;
    }


    focusOutFunctionblur(event: any) {
        if (event.keyCode === 13) {
            $('#input-email-approver').blur();
        }
    }

    showModalAddUser() {
        this.errorMsg = '';
        $('#modalSelectUser').modal().show();
        this.setupDataCheckbox();
    }

    removeError() {
        if (this.errorMsg === 'Công việc làm ngoài giờ của ai đó đang trống!') {
            this.errorMsg = '';
        }
        if (this.errorMsg === 'Thời gian làm ngoài giờ đang không đúng!') {
            this.errorMsg = '';
        }

    }

    initTooltip(): void {
        $('[rel="tooltip"]').tooltip();
    }

    deleteUserOfOT(id: number) {
        this.userSelecteds = this.userSelecteds.filter(item => item.id !== id);
    }

    private setupDataCheckbox() {
        this.dataRows.forEach(item => {
            item.is_selected = false;
        });
        this.userIds.length = 0;
        this.userSelecteds.forEach(item => {
            if (this.dataRows.find(element => {
                return element.id === item.id;
            }) !== undefined) {
                this.dataRows.find(element => {
                    return element.id === item.id;
                }).is_selected = true;
            }
            this.userIds.push(item.id);
        });

    }


    initTooltipContent(row: UserOT) {
        $('#input-content' + row.id).attr('data-original-title', row.work_content);
    }

    pasteContentWork(index: number, type: string) {
        switch (type) {
            case 'up':
                this.userSelecteds[index - 1].work_content = this.userSelecteds[index].work_content;
                break;
            case 'down':
                this.userSelecteds[index + 1].work_content = this.userSelecteds[index].work_content;
                break;
            case 'all':
                this.userSelecteds.forEach(item => {
                    item.work_content = this.userSelecteds[index].work_content;
                });
                break;
        }

    }

    private validateWc(over_time_details: any) {
        let check = true;
        over_time_details.forEach(item => {
            if (item.content === '') {
                check = false;
            }
        });
        return check;
    }

    deleteOT() {
        swal({
            title: 'Xóa đơn đăng ký làm ngoài giờ?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 1000);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            this.isDeleteOT = true;
            this.overtimeService.delete(this.paramOvertimeId).subscribe(
                repo => {
                    this.isDeleteOT = false;
                    swal({
                            title: 'Thành công',
                            text: 'Đơn đăng ký Làm ngoài giờ đã được xóa!',
                            type: 'success',
                            allowOutsideClick: false,
                        }
                    ).then(r => {
                        window.history.back();
                    }, f => {
                        window.history.back();
                    });
                },
                error => {
                    this.isDeleteOT = false;
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }

    pasteTime(id, index, type: string) {
        switch (type) {
            case 'up':
                const d = this.userSelecteds[index - 1].id;
                $('#start_datetime_' + d).val($('#start_datetime_' + id).val().trim());
                $('#end_datetime_' + d).val($('#end_datetime_' + id).val().trim());
                break;
            case 'down':
                const valID = this.userSelecteds[index + 1].id;
                $('#start_datetime_' + valID).val($('#start_datetime_' + id).val().trim());
                $('#end_datetime_' + valID).val($('#end_datetime_' + id).val().trim());
                break;
            case 'all':
                this.userSelecteds.forEach(item => {
                    $('#start_datetime_' + item.id).val($('#start_datetime_' + id).val().trim());
                    $('#end_datetime_' + item.id).val($('#end_datetime_' + id).val().trim());
                });
                break;
            case 'copy':
                this.userSelecteds.forEach(item => {
                    this.wCoppyStartTime = $('#start_datetime_' + id).val().trim();
                    this.wCoppyEndTime = $('#end_datetime_' + id).val().trim();
                });
                break;
            case 'paste':
                if (this.wCoppyStartTime !== '' && this.wCoppyEndTime !== '') {
                    this.userSelecteds.forEach(item => {
                        $('#start_datetime_' + id).val(this.wCoppyStartTime);
                        $('#end_datetime_' + id).val(this.wCoppyEndTime);
                    });
                }
                break;
        }

    }

    searchEmail(value: string) {
        if (value.length >= 2) {
            this.sub8 = this.dataGlobalService.searchUserWithEmail(value).subscribe(
                repo => {
                    this.datalistEmail = repo.users as any[];
                },
                error => {
                    this.dataGlobalService.actionFail(error.message);
                }
            );
        } else {
            this.datalistEmail.length = 0;
        }

    }
}

