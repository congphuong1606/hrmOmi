import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute, Router} from '@angular/router';
import {DataGlobalService} from '../../../services/data.global.service';
import {CategoryService} from '../../../services/category/category.service';
import {RoleService} from '../../../services/role.service';
import {CategoryOtherService} from '../../../services/category/category-other.service';
import {CourseService} from '../../../services/course/course.service';
import {Training, UserCourseRepose} from '../../../models/api/response/CourseRepose';
import {Subscription} from 'rxjs/Subscription';

declare var $: any;
declare var swal: any;

declare interface Score {
    trainingId: number;
    score: number;
    isChange: boolean;
};

declare interface ScoreRequest {
    id: number;
    score: number;
};

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
    selector: 'core-of-course-cmp',
    moduleId: module.id,
    templateUrl: 'core-of-course.component.html'
})

export class CoreOfCourseComponent implements OnInit, OnDestroy {
    isLoadSuccess = false;
    userOfCourses: Training[] = [];
    currentPage = 1;
    lastPage = 1;
    perPage = 10;
    total = 0;
    idCourse: number;
    sessionPresenceDefautl: any[] = [];
    trainingDefautl: Training;
    isChangePresence = false;
    isEditCore = false;
    scoreDefaults: Score[] = [];
    isImportFile = false;
    loadingImport = true;
    importFileForm: FormGroup;
    reviewFileImport = false;
    listDataImport: string[][] = [];
    sub: Subscription;
    sub5: Subscription;
    sub1: Subscription;
    sub2: Subscription;
    sub3: Subscription;
    sub4: Subscription;
    curentFileImportId: number;
    sub6: Subscription;
    sub7: Subscription;
    isSendResultPass = false;


    constructor(private router: Router,
                private fb: FormBuilder,
                private roleService: RoleService,
                private courseService: CourseService,
                private route: ActivatedRoute,
                private categoryService: CategoryService,
                private cateOtherService: CategoryOtherService,
                public dataService: DataGlobalService) {
    }

    ngOnInit() {
        if (!this.dataService.checkPemisson('/dao-tao/quan-ly-nhan-vien-trong-khoa-hoc')) {
            window.history.back();
        } else {
            this.getPramRouter();
            if (this.idCourse !== undefined) {
                this.getDataUserOfCourse();
            }
        }
    }

    createImportFileForm() {
        this.importFileForm = this.fb.group({
            fileScore: [''],
        });
    }

    uploadFile() {
        this.loadingImport = false;
        const body = {
            course_id: this.idCourse,
            file: this.importFileForm.get('fileScore').value === null || this.importFileForm.get('fileScore').value.length === 0 ? '' :
                this.importFileForm.get('fileScore').value[0],
        };
        if (body.file !== '') {
            this.sub2 = this.courseService.uploadFileScore(body).subscribe(
                data => {
                    this.loadingImport = true;
                    this.curentFileImportId = data.course_score_excel_file_id as number;
                    // this.listFiles = data.files;
                    if (this.curentFileImportId !== undefined) {
                        swal({
                            title: 'Xác nhận nhập file điểm ',
                            text: 'Nhập luôn file này mà không cần xem lại không ?',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Đồng ý',
                            cancelButtonText: 'Xem lại',
                            showLoaderOnConfirm: true,
                            preConfirm: function () {
                                return new Promise(resolve => {
                                    setTimeout(function () {
                                        resolve();
                                    }, 500);
                                });
                            },
                            allowOutsideClick: true
                        }).then(
                            result => {
                                this.importFile();
                            },
                            dismiss => {
                                this.getFileImportDetail(this.curentFileImportId);
                                $('#review-file-import').modal('show');
                            });
                    }
                    window.location.href = window.location.href.split('#')[0] + '#list-files';
                },
                error => {
                    this.loadingImport = true;
                    this.dataService.actionFail(error.error);
                }
            );
        } else {
            this.loadingImport = true;
        }

    }

    importFile() {
        this.sub5 = this.courseService.applyImportFile(this.curentFileImportId).subscribe(
            data => {
                swal({
                    title: 'Nhập thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Đóng',
                });
                this.getDataUserOfCourse();
                this.closeFormIport();
                this.isImportFile = false;
                this.isEditCore = false;
                this.isChangePresence = false;

            }, error => {
                this.dataService.actionFail(error.error);
            });
    }


    getDataUserOfCourse() {
        // this.isLoadSuccess = false;

        this.sub = this.courseService.getCoreForUser(this.idCourse).subscribe(
            data => {
                console.log(data);
                this.userOfCourses = (data.training as Training[]);
                this.updateCoreDefault();
                this.sub !== undefined ? this.sub.unsubscribe() : console.log('');
                this.isLoadSuccess = true;

            },
            error1 => {
                this.isLoadSuccess = true;
                this.sub !== undefined ? this.sub.unsubscribe() : console.log('');
                this.dataService.actionFail(error1.error);
            }
        );
    }

    initTooltip(): void {
        $('[rel="tooltip"]').tooltip();
    }

    getPramRouter(): void {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.idCourse = parseInt(queryParamMap.get('id_course') === null ? undefined : queryParamMap.get('id_course'));
    }

    onBack(): void {
        this.ngOnDestroy();
        window.history.back();
    }


    ngOnDestroy(): void {
        this.sub !== undefined ? this.sub.unsubscribe() : console.log('');
        this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
        this.sub2 !== undefined ? this.sub2.unsubscribe() : console.log('');
        this.sub3 !== undefined ? this.sub3.unsubscribe() : console.log('');
        this.sub4 !== undefined ? this.sub4.unsubscribe() : console.log('');
        this.sub5 !== undefined ? this.sub5.unsubscribe() : console.log('');
        this.sub6 !== undefined ? this.sub6.unsubscribe() : console.log('');
        this.sub7 !== undefined ? this.sub7.unsubscribe() : console.log('');
    }

    // cap nhat diem danh
    showSesionPresence(row: Training) {
        this.trainingDefautl = row;
        this.sessionPresenceDefautl.length = 0;
        this.trainingDefautl.sessions.forEach(item => {
            this.sessionPresenceDefautl.push({
                sId: item.id,
                startTime: item.start_datetime,
                endTime: item.end_datetime,
                sPresence: item.presence
            });
        });
        this.isChangePresence = false;
        $('#view-presence').modal('show');
    }

    changePresence(sp: any) {
        sp.sPresence === 1 ? sp.sPresence = 0 : sp.sPresence = 1;
        let flag = 1;
        this.trainingDefautl.sessions.forEach(item => {
            const presence = this.sessionPresenceDefautl.find(
                element => {
                    return element.sId === item.id;
                }
            );
            if (presence.sPresence !== item.presence) {
                flag = 0;
            }
        });
        flag === 0 ? this.isChangePresence = true : this.isChangePresence = false;
        this.updatePresance();
    }

    updatePresance() {
        let body = {
            user_id: this.trainingDefautl.user_id,
            details: [],
        }
        this.sessionPresenceDefautl.forEach(item => {
            body.details.push({session_id: item.sId, presence: item.sPresence});
        });
        this.sub3 = this.courseService.updatePresence(body).subscribe(
            data => {
                this.getDataUserOfCourse();
            },
            error => {
                console.log(error);
                this.dataService.actionFail(error.error);
            }
        );
    }

    onScoreChangeValue(value: any, row: Training) {
        if (value.trim() > 10) {
            value = 10;
        }
        if (value.trim() < 0) {
            value = 0;
        }
        row.score = value.trim() === '' ? null : value.trim();
        const scoreItem = this.scoreDefaults.find(
            element => {
                return element.trainingId === row.id;
            }
        );
        if (scoreItem.score !== row.score) {
            scoreItem.isChange = true;
        } else {
            scoreItem.isChange = false;
        }
    }


    changeEditScore(b: boolean) {
        $('[rel="tooltip"]').tooltip('hide');
        this.isEditCore = b;
        let flag = false;
        this.scoreDefaults.forEach(item => {
            if (item.isChange) {
                flag = true;
            }
        });
        if (flag) {
            let scoreRequests: ScoreRequest[] = [];
            this.userOfCourses.forEach(item => {
                if (item.score !== null) {
                    scoreRequests.push({id: item.id, score: parseFloat('' + item.score)});
                } else {
                    scoreRequests.push({id: item.id, score: parseFloat('-1')});
                }

            });
            this.updateCoreToDB({training: scoreRequests});
        }
    }


    checkChangescore(row: Training) {
        const scoreItem = this.scoreDefaults.find(
            element => {
                return element.trainingId === row.id;
            }
        );
        return scoreItem.isChange ? true : false;
    }

    updateCoreDefault() {
        this.scoreDefaults.length = 0;
        this.userOfCourses.forEach(item => {
            this.scoreDefaults.push({trainingId: item.id, score: item.score, isChange: false});
        });
    }

    updateCoreRow(row: Training) {
        $('[rel="tooltip"]').tooltip('hide');
        this.updateCoreToDB({training: [{id: row.id, score: row.score}]});
    }

    updateCoreToDB(body: { training: ScoreRequest[] }) {
        this.sub1 = this.courseService.updateScore(body).subscribe(
            data => {
                this.updateCoreDefault();
                this.isEditCore = false;
                swal('Cập nhật điểm thành công');
            },
            error => {
                console.log(error);
                this.dataService.actionFail(error.error);
            }
        );
    }

    togglebtn(type: string) {
        switch (type) {
            case 'importScore':
                this.createImportFileForm();
                this.isImportFile = true;
                break;
            case 'sendResultPass':
                this.sendResultPass();
                break;
            case 'sendResultFail':
                break;
            case 'pdf-download':
                this.downloadPDF();
                break;
        }

    }

    closeFormIport() {
        this.isImportFile = false;
    }


    sendResultPass() {
        swal({
            title: 'Thông báo kết quả',
            text: 'Gửi kết quả cho mọi người?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d62839',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },
            allowOutsideClick: true
        }).then(
            result => {
                this.isSendResultPass = true;
                this.sub7 = this.courseService.sendResult(this.idCourse).subscribe(
                    data => {
                        swal('Thông báo kết quả thành công!');
                        setTimeout(a => {
                            this.isSendResultPass = false;
                        }, 1000);
                    }, error => {
                        setTimeout(a => {
                            this.isSendResultPass = false;
                        }, 1000);
                        this.dataService.actionFail(error.error);
                    });
            }
        );
    }

    getFileImportDetail(courseScoreExcelFileId: number) {
        this.listDataImport.length = 0;
        this.reviewFileImport = false;
        this.sub4 = this.courseService.getDetailFileImport(courseScoreExcelFileId).subscribe(
            data => {
                this.listDataImport = data.details as string[][];
                this.reviewFileImport = true;
            }, error => {
                this.reviewFileImport = true;
                this.dataService.actionFail(error.error);
            });
    }

    downloadPDF() {
        this.dataService.disBtnSubmit();
        this.sub6 = this.courseService.downloadPDFScore(this.idCourse).subscribe(
            data => {
                const fileURL = URL.createObjectURL(data);
                window.open(fileURL);
                this.dataService.enableBtnSubmit();
            }, error1 => {
                this.dataService.enableBtnSubmit();
                this.dataService.actionFail(error1.error);
            }
        );
    }


}



