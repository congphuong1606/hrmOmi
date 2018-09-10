import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute, Router} from '@angular/router';
import {DataGlobalService} from '../../../services/data.global.service';
import {CategoryService} from '../../../services/category/category.service';
import {CategoryOtherReponse, CategoryReponse} from '../../../models/api/response/CategoriesReponse';
import {Subscription} from 'rxjs/Subscription';
import {RoleService} from '../../../services/role.service';
import {CategoryOtherService} from '../../../services/category/category-other.service';
import * as moment from 'moment';
import {CourseService} from '../../../services/course/course.service';
import {OneCourse, UserCourseRepose} from '../../../models/api/response/CourseRepose';


declare var $: any;
declare var swal: any;

declare interface Session {
    name: string;
    id: string;
    active: boolean;
    error: ErrorSesision;
}

declare interface ErrorSesision {
    errorStartTime: string;
    errorEndTime: string;
    errorTrainer: string;
    errorSupporter: string;
    errorContent: string;
    errorUserIds: string;
}

declare interface FormG {
    id: number;
    form: FormGroup;
    session: Session;
    userIds: Number[];
    userSelecteds: UserCourseRepose[];
    errorForm: boolean;
}


@Component({
    selector: 'update-course-cmp',
    moduleId: module.id,
    templateUrl: 'update-course.component.html'
})

export class UpdateCourseComponent implements OnInit, OnDestroy {
    numDate: number;
    listOfmodal: any[] = [];
    departments: CategoryReponse[] = [];
    categoryCourses: CategoryOtherReponse[] = [];
    officePositions: CategoryReponse[] = [];
    jobStatuss: CategoryReponse[] = [];
    rooms: CategoryOtherReponse[] = [];
    dataRows: UserCourseRepose[] = [];

    isFisrtTimes1 = true;
    isFisrtTimes2 = true;


    categoryCoursename = 'Chọn khóa học';
    roomName = 'Chọn phòng học';
    typeModal = '';
    departmentsName = 'Chọn phòng ban';
    officePositionName = 'Chọn chức danh';
    JobStatusName = 'Chọn trạng thái cv';
    formG: FormG[] = [];
    isAll = false;
    idFormCurent = 1;
    course_category_id: number;
    room_category_id: number;
    course_description = '';
    errorCourseCategory = '';
    errorRoomCategory = '';
    userIds: Number[] = [];

    department_id = '';
    job_position_id = '';
    job_status_id = '';
    search_value = '';
    idCourse: number;
    isLoadSuccess = false;
    currentCourse: OneCourse = new OneCourse();
    sub1: Subscription;
    sub2: Subscription;
    sub3: Subscription;
    sub4: Subscription;
    sub5: Subscription;
    sub6: Subscription;
    sub7: Subscription;
    sub8: Subscription;
    sub9: Subscription;
    check1 = false;
    check2 = false;
    check3 = false;
    check4 = false;
    check5 = false;
    check6 = false;
    check7 = false;
    check8 = false;
    check9 = false;

    constructor(private router: Router,
                private fb: FormBuilder,
                private route: ActivatedRoute,
                private roleService: RoleService,
                private courseSevice: CourseService,
                private serrvice: CategoryService,
                private cateOtherService: CategoryOtherService,
                public dataService: DataGlobalService) {
    }

    ngOnDestroy(): void {
        this.sub1 !== undefined ? this.sub1.unsubscribe() : this.check1 = true;
        this.sub2 !== undefined ? this.sub2.unsubscribe() : this.check2 = true;
        this.sub3 !== undefined ? this.sub3.unsubscribe() : this.check3 = true;
        this.sub4 !== undefined ? this.sub4.unsubscribe() : this.check4 = true;
        this.sub5 !== undefined ? this.sub5.unsubscribe() : this.check5 = true;
        this.sub6 !== undefined ? this.sub6.unsubscribe() : this.check6 = true;
        this.sub7 !== undefined ? this.sub7.unsubscribe() : this.check7 = true;
        this.sub9 !== undefined ? this.sub9.unsubscribe() : this.check9 = true;
        this.sub8 !== undefined ? this.sub8.unsubscribe() : console.log('');
    }

    ngOnInit() {
        if (!this.checkPemision('/dao-tao/cap-nhat-khoa-hoc')) {
            window.history.back();
        } else {
            this.getPramRouter();
            this.idCourse !== undefined ? this.getDataOfCourse() : console.log(':D Thiếu ID COURSE');
            // this.formG.length === 0 ? this.createFormG() : console.log(':D');
            this.numDate = Date.now();
        }

    }

     getDataOfCourse() {
        this.sub9 = this.courseSevice.get(this.idCourse).subscribe(
            data => {
                this.currentCourse = data.course as OneCourse;
                this.getData();
                this.check9 = true;
            },
            error1 => {
                this.check9 = true;
                this.dataService.actionFail(error1.error);
            }
        );
    }

    getPramRouter(): void {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.idCourse = parseInt(queryParamMap.get('id_course') === null ? undefined : queryParamMap.get('id_course'));
    }

    selectAllItem(): void {
        this.isAll = !this.isAll;
        if (this.isAll) {
            this.dataRows.forEach(element => {
                let item: Number = -1;
                item = this.userIds.find(id => {
                    return id === element.id;
                });
                item !== -1 ? this.userIds.push(element.id) : console.log(':D')
                element.is_selected = true;

            });
        } else {
            this.dataRows.forEach(element => {
                this.userIds = this.userIds.filter(id => id !== element.id);
                element.is_selected = false;
            });
        }

    }

    selectItemEmployee(row: UserCourseRepose): void {
        row.is_selected = !row.is_selected;
        if (row.is_selected) {
            this.userIds.push(row.id);
        } else {
            this.userIds = this.userIds.filter(h => h !== row.id);
        }
        this.checkAll();
    }

    saveUserSelected(): void {
        this.formG.find(obj => {
            return obj.id === this.idFormCurent;
        }).userSelecteds.length = 0;
        this.sub8 = this.courseSevice.searchUser('', '', '', '').subscribe(
            data => {
                (data.users as UserCourseRepose[]).forEach(element => {
                    const item = this.userIds.find(id => {
                        return id === element.id;
                    });
                    if (item !== undefined) {
                        this.formG.find(obj => {
                            return obj.id === this.idFormCurent;
                        }).userSelecteds.push(element);
                    }
                });
            }, error1 => {
                this.dataService.actionFail(error1.error);
            }
        );
        this.formG.find(obj => {
            return obj.id === this.idFormCurent;
        }).userIds = this.userIds;
        if (this.userIds.length > 0) {
            this.formG.find(obj => {
                return obj.id === this.idFormCurent;
            }).session.error.errorUserIds = '';
        }
    }


     getData() {
        this.sub1 = this.serrvice.getList('job_status').subscribe(
            repo => {
                this.check1 = true;
                this.jobStatuss = (repo.jobs_status as CategoryReponse[]);
            },
            error => {
                this.check1 = true;
                this.dataService.actionFail(error.error);
            });
        this.sub2 = this.serrvice.getList('positions').subscribe(
            repo => {
                this.check2 = true;
                this.officePositions = (repo.positions as CategoryReponse[]);
            },
            error => {
                this.check2 = true;
                this.dataService.actionFail(error.error);
            });
        this.sub3 = this.serrvice.getList('departments').subscribe(
            repo => {
                this.check3 = true;
                this.departments = (repo.departments as CategoryReponse[]);
            },
            error => {
                this.check3 = true;
                this.dataService.actionFail(error.error);
            });
        this.sub4 = this.cateOtherService.getList('categoryCourses').subscribe(
            repo => {
                this.check4 = true;
                this.categoryCourses = repo.item_category as CategoryOtherReponse[];

                this.course_category_id = this.currentCourse.course_category_id;
                this.categoryCourses.forEach(element => {
                    if (element.id === this.course_category_id) {
                        this.categoryCoursename = element.name;
                        this.course_description = element.description;
                    }
                });
            },
            error => {
                this.check4 = true;
                this.dataService.actionFail(error.message);
            });
        this.sub5 = this.cateOtherService.getList('room').subscribe(
            repo => {
                this.check5 = true;
                this.rooms = repo.item_category as CategoryOtherReponse[];
                this.room_category_id = this.currentCourse.room_category_id;
                this.rooms.forEach(element => {
                    if (element.id === this.room_category_id) {
                        this.roomName = element.name;
                    }
                });
            },
            error => {
                this.check5 = true;
                this.dataService.actionFail(error.message);
            });
        this.getListEmployees();

    }


    getListEmployees(): void {
        this.sub6 = this.courseSevice.searchUser(this.department_id,
            this.job_position_id, this.search_value, '').subscribe(
            repo => {
                this.dataRows.length = 0;
                this.dataRows = repo.users as UserCourseRepose[];
                if (this.isFisrtTimes1) {
                    this.isFisrtTimes1 = false;
                    this.userIds = this.currentCourse.sessions[0].user_ids;
                }
                console.log(this.userIds);
                console.log(this.dataRows)
                this.userIds.forEach(idSelected => {
                    if (this.dataRows.find(row => {
                        return row.id === idSelected;
                    }) !== undefined) {
                        this.dataRows.find(row => {
                            return row.id === idSelected;
                        }).is_selected = true;
                    }
                });
                this.checkAll();
                if (this.isFisrtTimes2) {
                    this.isFisrtTimes2 = false;
                    this.creatForms();
                }
                this.isLoadSuccess = true;
                this.check6 = true;
                $('[rel="tooltip"]').tooltip();
            },
            error => {
                this.check6 = true;
                this.isLoadSuccess = true;
                this.dataService.actionFail(error.message);
            }
        );
    }

    checkAll(): void {
        let flag = true;
        this.dataRows.forEach(element => {
            if (element.is_selected === false) {
                flag = false;
            }
        });
        flag ? this.isAll = true : this.isAll = false;
    }

    createFormG(): void {
        const formGIndex = this.formG.length + 1;
        const newSession = {
            name: 'Buổi ' + formGIndex,
            id: 'session' + formGIndex,
            active: formGIndex !== 1 ? false : true,
            error: {
                errorStartTime: '',
                errorEndTime: '',
                errorTrainer: '',
                errorSupporter: '',
                errorContent: '',
                errorUserIds: '',
            }
        };
        const newFormG = {
                id: formGIndex,
                form: this.fb.group({
                    start_datetime: [''],
                    end_datetime: [''],
                    trainer: ['', Validators.compose([Validators.required, Validators.email])],
                    supporter: ['', Validators.compose([Validators.required])],
                    content: ['', Validators.compose([Validators.required])],
                }),
                userIds: [],
                session: newSession,
                userSelecteds: [],
                errorForm: true,
            }
        ;
        this.formG.push(newFormG);
    }

     creatForms() {
        this.currentCourse.sessions.forEach(session => {
            const formGIndex = this.formG.length + 1;
            const newSession = {
                name: 'Buổi ' + formGIndex,
                id: 'session' + formGIndex,
                active: formGIndex !== 1 ? false : true,
                error: {
                    errorStartTime: '',
                    errorEndTime: '',
                    errorTrainer: '',
                    errorSupporter: '',
                    errorContent: '',
                    errorUserIds: '',
                }
            };
            const newFormG = {
                    id: formGIndex,
                    form: this.fb.group({
                        start_datetime: [session.start_datetime],
                        end_datetime: [session.end_datetime],
                        trainer: [session.trainer, Validators.compose([Validators.required, Validators.email])],
                        supporter: [session.supporter, Validators.compose([Validators.required])],
                        content: [session.content, Validators.compose([Validators.required])],
                    }),
                    userIds: session.user_ids,
                    session: newSession,
                    userSelecteds: this.getUserSelect(session.user_ids) as UserCourseRepose[],
                    errorForm: true,
                }
            ;
            this.formG.push(newFormG);
        });
    }

    getUserSelect(user_ids: number[]): any {
        let list: UserCourseRepose[] = [];
        user_ids.forEach(id => {
            if (this.dataRows.find(row => {
                return row.id === id;
            }) !== undefined) {
                list.push(this.dataRows.find(row => {
                    return row.id === id;
                }));
            }
        });
        return list;
    }

    changeTab(formGId: number): void {

        // thay đổi tab
        this.formG.forEach(element => {
            if (element.id === formGId) {
                element.session.active = true;
                this.userIds = element.userIds;
            } else {
                element.session.active = false;
            }
        });
        // thay đổi dữ liệu data
        this.dataRows.forEach(element => {
            element.is_selected = false;
        });
        this.userIds.forEach(id => {
            this.dataRows.find(row => {
                return row.id === id;
            }).is_selected = true;
        });
        // kiểm tra check all
        this.checkAll();
    }


    checkPemision(path: string): boolean {
        return this.dataService.checkPemisson(path);
    }

    initSelect(): void {
        if ($('.selectpicker').length !== 0) {
            $('.selectpicker').selectpicker();
        }
    }


    updateDepartment(data: any): void {
        this.router.navigate(['../danh-muc/cap-nhat-phong-ban'], {queryParams: data});
    }

    addDepartment(): void {
        this.router.navigate(['../danh-muc/them-phong-ban']);
    }


    deleteDepartment(data: any): void {
        swal({
            title: 'Xác nhận xóa?',
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
                    }, 500);
                });
            }
            ,
            allowOutsideClick: false
        }).then(result => {
            this.serrvice.delete('departments', data.id).subscribe(
                repo => {
                    if (repo.status === 'success') {
                        swal('Đã Xóa!', 'Bạn đã xóa phòng ban thành công', 'success');
                        this.dataRows = this.dataRows.filter(h => h !== data);
                    }
                },
                error => {
                    this.dataService.actionFail(error.error);
                });

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

    updateCourse(): void {
        let sessions: any[] = [];
        this.formG.forEach(form => {
            this.checkValidateForm(form);
            const sesion = {
                start_datetime: $('#start_datetime_' + form.session.id).val().trim(),
                end_datetime: $('#end_datetime_' + form.session.id).val().trim(),
                trainer: form.form.get('trainer').value,
                supporter: form.form.get('supporter').value,
                content: form.form.get('content').value,
                user_ids: form.userIds,
            };
            sessions.push(sesion);
        });
        const bodyCourse = {
            course_category_id: this.course_category_id,
            description: this.course_description,
            room_category_id: this.room_category_id,
            sessions: sessions,
        };

        bodyCourse.course_category_id !== undefined ? this.errorCourseCategory = '' : this.errorCourseCategory = 'Vui lòng chọn khóa học !';
        bodyCourse.room_category_id !== undefined ? this.errorRoomCategory = '' : this.errorRoomCategory = 'Vui lòng chọn phòng !';
        if (this.errorCourseCategory === '' && this.errorRoomCategory === '') {
            let flag = true;
            this.formG.forEach(element => {
                if (element.session.error.errorStartTime !== '' ||
                    element.session.error.errorEndTime !== '' ||
                    element.session.error.errorUserIds !== '' ||
                    element.form.get('trainer').hasError('email')||
                    (!element.form.get('supporter').hasError('required') &&
                        element.form.get('supporter').hasError('email') &&
                        element.form.get('supporter').touched)) {
                    flag = false;
                }
            });
            if (flag) {
                this.update(bodyCourse);
            }
            console.log(flag)
        }
    }

     checkValidateForm(form: FormG) {
        const startTime = $('#start_datetime_' + form.session.id).val().trim();
        const endTime = $('#end_datetime_' + form.session.id).val().trim();
        startTime === '' ? form.session.error.errorStartTime = 'Vui lòng chọn thời gian bắt đầu !' : form.session.error.errorStartTime = '';
        endTime === '' ? form.session.error.errorEndTime = 'Vui lòng chọn thời gian kết thúc !' : form.session.error.errorEndTime = '';
        if (startTime !== '' && endTime !== '') {
            const date1 = moment(startTime, 'DD-MM-YYYY HH:mm');
            const date2 = moment(endTime, 'DD-MM-YYYY HH:mm');
            const duration1 = moment.duration(date2.diff(date1));
            if (duration1.asMinutes() > 0) {
                form.session.error.errorEndTime = '';
            } else {
                form.session.error.errorEndTime = 'Thời gian kết thúc không đúng !';
            }
        }
        form.userSelecteds.length === 0 ? form.session.error.errorUserIds = 'Hãy chọn thành viên được đào tạo !' : form.session.error.errorUserIds = '';
    }


    onBack(): void {
        this.ngOnDestroy();
        window.history.back();
    }


    goToSelectEmployees(): void {
        this.router.navigate(['../dao-tao/tuy-chinh-doi-tuong-dao-tao-cho-khoa-hoc']);
    }


    getValue(): void {
        // this.start1 = this.create_course_form.get('\'session\' + index,').get('proposer_email').value;
    }


    initDatePicker(idDateTimePicker: string, idSession: number, id: number): void {
        $('#' + idDateTimePicker + idSession).datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            ignoreReadonly: true,
            sideBySide: true,
            daysOfWeekDisabled: [0, 6],
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
        }).attr('readonly', 'readonly').on('dp.change', e => {
            this.formG.forEach(item => {
                if (item.id === id) {
                    idDateTimePicker === 'end_datetime_' ? item.session.error.errorEndTime = '' : item.session.error.errorStartTime = '';
                }
            });
        });
    }


    selectItem(course: CategoryOtherReponse): void {
        if (this.typeModal === 'listCategoryCourse') {
            this.categoryCoursename = course.name;
            this.course_description = course.description;
            this.course_category_id = course.id;
            this.errorCourseCategory = '';
        } else {
            this.roomName = course.name;
            this.room_category_id = course.id;
            this.errorRoomCategory = '';
        }
    }


    showModal(typeModal: string): void {
        this.typeModal = typeModal;
        if (typeModal === 'listCategoryCourse') {
            this.listOfmodal = this.categoryCourses;
        } else {
            this.listOfmodal = this.rooms;
        }
        $('#modalList').modal('show');
    }

    showModalSelect(id: number): void {
        this.idFormCurent = id;
        $('#selectEmployees').modal('show');
    }

    onSearchValueChange(searchValue: string) {
        this.search_value = searchValue.trim();
    }

    actionSearchValue(): void {
        this.isLoadSuccess = false;
        this.getListEmployees();
    }


    removeSessionForm(id: number) {
        swal({
            title: 'Xác nhận xóa?',
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
                    }, 500);
                });
            }
            ,
            allowOutsideClick: false
        }).then(res => {
            this.formG = this.formG.filter(form => form.id !== id);
        }).catch(swal.noop);

    }

     update(bodyCourse: { course_category_id: number; description: string; room_category_id: number; sessions: any[] }) {
        swal({
            title: 'Cập nhật khóa học ?',
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
                    }, 500);
                });
            }
            ,
            allowOutsideClick: false
        }).then(res => {
            this.sub7 = this.courseSevice.update(this.idCourse, bodyCourse).subscribe(
                repo => {
                    swal({
                        title: 'Thành công!',
                        text: 'Bạn vừa cập nhật thông tin khóa học',
                        type: 'success',
                        confirmButtonText: 'Thoát'
                    }).then(isConfirm => {
                        if (isConfirm) {
                            window.history.back();
                        }
                    }).catch(swal.noop);
                },
                error => {
                    this.dataService.actionFail(error.error);
                }
            );
        }).catch(swal.noop);

    }

     gotoCateGoryOther(type: string): void {
        let item: any;
        if (type === 'room') {
            item = {
                name: 'Các phòng',
                path: 'cac-phong',
                type: 'room',
            };
        }
        if (type === 'course') {
            item = {
                name: 'Các khóa học',
                path: 'cac-khoa-hoc',
                type: 'categoryCourses',
            };
        }
        this.router.navigate(['../danh-muc/danh-sach-trong-danh-muc-khac/', item.path], {
            queryParams: {
                category_type: item.type,
                category_name: item.name,
            }
        });
    }

}