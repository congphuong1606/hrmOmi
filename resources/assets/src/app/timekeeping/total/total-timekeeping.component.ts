import { Component, OnInit, AfterViewInit, AfterViewChecked } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd } from '@angular/router';
import { PersonnelService } from "../../services/personnel.service";
import { LoaderController } from "../../shared/loader/loader";
declare var $: any;
declare var swal: any;
import * as moment from 'moment';
import { Department } from "../../models/api/request/ListEmployeesRequest";
import { DataGlobalService } from "../../services/data.global.service";
import { JobStatus } from "../../models/api/response/ListEmployeesResponse";

@Component({
    selector: 'total-timekeeping-cmp',
    moduleId: module.id,
    templateUrl: 'total-timekeeping.component.html'
})

export class TotalTimekeepingComponent implements OnInit, AfterViewInit, AfterViewChecked {

    zIndex = 10000;
    currentCellId = '';

    ngAfterViewInit(): void {
        $('[rel="tooltip"]').tooltip();
    }

    form: FormGroup
    loaderController: LoaderController = new LoaderController();
    timeOns: any = [];
    daysInMonth: any = [];
    token = '';

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private authService: AuthService,
        private personnelService: PersonnelService,
        private globalDataService: DataGlobalService,
        private router: Router) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.token = this.authService.getToken();
    }

    ngOnInit() {
        if (!this.globalDataService.checkPemisson('/cham-cong/bang-tong')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.loaderController.enableLoader();
            $('[rel="tooltip"]').tooltip();
            const defaultDepartment = new Department();
            defaultDepartment.id = 0;
            defaultDepartment.name = 'Tất cả';
            this.listDepartments.unshift(defaultDepartment);
            this.department = this.listDepartments[0];
            this.getListDepartments();
            this.getListJobStatus();
            this.getTotalListTimeKeepingMonths();
        }

    }

    showModalNote = false;

    onCloseModalNote(selectedTimeOn) {
        if (selectedTimeOn !== null) {
            this.timeOns.forEach((element, key) => {
                if (element.id === selectedTimeOn.id) {
                    this.timeOns[key] = selectedTimeOn;
                }
            });
        }
        this.showModalNote = false;
    }
    selectedTimeOn: any;
    openModalAccumulated(timeOn) {
        this.showModalNote = true;
        this.selectedTimeOn = timeOn;
        console.log(this.selectedTimeOn);
    }
    

    ngAfterViewChecked() {
        $('[rel="tooltip"]').tooltip();
    }
    listMonths = [];
    selectedMonth: any = null;
    employeeName = '';
    employeeCode = '';
    getTotalListTimeKeepingMonths() {
        this.loaderController.pushLoader();
        this.personnelService.getTotalListTimeKeepingMonths().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listMonths = data.months;
                if (this.listMonths.length) {
                    this.selectedMonth = this.listMonths[0];
                }
                if (this.selectedMonth !== null) {
                    this.getTimeOn();
                }
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }


    timeOffs: any = [];
    holidays: any = [];
    overTimes: any = [];
    day_off_late_without_permit = 0;
    day_off_late_permit = 0;
    day_off_without_permit = 0;
    day_off_go_out = 0;
    day_off_late_ot = 0;
    day_off_leave_early = 0;
    day_off_leave_early_without_permit = 0;
    showPopup(id: any, timeon) {
        this.zIndex++;
        // const popup = document.getElementById(id);
        // popup.style.zIndex = this.zIndex + '';
        // popup.classList.toggle("show");
        const container = document.getElementById('timekeeping-container').firstElementChild;
        const parent = document.getElementById(id)
        const popup = document.getElementById('popup-container');
        if (parent.id === this.currentCellId) {
            popup.classList.toggle("show");
        } else {
            popup.classList.add('show');
        }
        const isShow = popup.classList.contains('show');
        if (isShow) {
            this.timeOffs = timeon.time_offs;
            this.holidays = timeon.holidays;
            this.overTimes = timeon.over_times;
            this.day_off_late_without_permit = timeon.day_off_late_without_permit;
            this.day_off_late_permit = timeon.day_off_late_permit;
            this.day_off_without_permit = timeon.day_off_without_permit;
            this.day_off_go_out = timeon.day_off_go_out;
            this.day_off_late_ot = timeon.day_off_late_ot;
            this.day_off_leave_early = timeon.day_off_leave_early_permit;
            this.day_off_leave_early_without_permit = timeon.day_off_leave_early_without_permit;

        }
        this.currentCellId = id;
        this.positionAt('top', container, parent, popup)
    }

    positionAt(position, container, parent, elem) {
        const left = parent.getBoundingClientRect().left - container.getBoundingClientRect().left - 250 + parent.offsetHeight / 2;
        const top = parent.getBoundingClientRect().top - container.getBoundingClientRect().top - 220 + 25;

        switch (position) {
            case 'top':
                elem.style.left = left + 'px';
                elem.style.top = top + 'px';
                break;

            case 'right':
                break;

            case 'bottom':
                break;
        }

    }
    timeOffsEmployee: any = [];
    holidaysEmployee: any = [];
    overTimesEmployee: any = [];
    day_off_late_without_permit_employee = 0;
    timeOn: any = null;
    showDetail(timeOn) {
        this.timeOn = timeOn;
        $('#timeon-detail').modal('show');
    }
    closeModal() {
        $('#timeon-detail').modal('hide');
    }

    dayOfWeek(date) {
        return moment(date, 'YYYY-MM-DD').day();
    }

    isWeekend(date) {
        const dayOfWeek = moment(date, 'YYYY-MM-DD').day();
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            return true;
        } else {
            return false;
        }
    }

    calculating() {
        this.loaderController.pushLoader();
        this.personnelService.calculatingTimeOn().subscribe(
            data => {
                this.loaderController.releaseLoader();
                if (!this.listMonths.length) {
                    this.getTotalListTimeKeepingMonths();
                } else {
                    this.getTimeOn();
                }
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    scrollLeft() {
        const container = document.getElementById('timekeeping-container');
        container.scrollLeft = container.scrollLeft - 50;
    }

    scrollRight() {
        const container = document.getElementById('timekeeping-container');
        container.scrollLeft = container.scrollLeft + 50;
    }

    intervalRight = null;
    mouseDownRight() {
        if (!this.intervalRight) {
            this.intervalRight = setInterval(function () {
                const container = document.getElementById('timekeeping-container');
                container.scrollLeft = container.scrollLeft + 50;
            }, 100);
        }
    }

    mouseUpRight() {
        if (this.intervalRight) {
            window.clearInterval((this.intervalRight));
            this.intervalRight = null;
        }
    }

    intervalLeft = null;
    mouseDownLeft() {
        if (!this.intervalLeft) {
            this.intervalLeft = setInterval(function () {
                const container = document.getElementById('timekeeping-container');
                container.scrollLeft = container.scrollLeft - 50;
            }, 100);
        }
    }

    mouseUpLeft() {
        if (this.intervalLeft) {
            window.clearInterval((this.intervalLeft));
            this.intervalLeft = null;
        }
    }
    currentMonth: any = '';
    currentYear:any = '';
    getTimeOn() {
        this.loaderController.pushLoader();
        this.personnelService.getTimeOn(this.selectedMonth, this.employeeName, this.employeeCode, this.department.id, this.job_status.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.timeOns = data.time_ons_month;
                this.daysInMonth = data.days_in_month;
                this.currentMonth = data.month;
                this.currentYear = data.year;
                this.timeOns.forEach((element, index) => {
                    element.time_ons.forEach((day, i) => {
                        const data = {
                            class: 'td-default',
                            title: '',
                            time: '',
                        };
                        if (this.isWeekend(day.date)) {
                            data.class = 'td-weekend';
                            data.title = 'Ngày cuối tuần';
                            data.time = '';
                        } else {
                            if (day.holidays.length) {
                                data.class = 'td-holiday';
                                data.title = 'Ngày nghỉ lễ';
                                data.time = '';
                            } else {
                                if (day.is_imported === 0 && day.is_updated === 0) {
                                    data.class = 'td-no-data';
                                    data.title = 'Không có dữ liệu';
                                    data.time = '';
                                } else {
                                    if (day.day_off_multi_permit) {
                                        data.class = 'td-multi-permit';
                                        data.title = 'Nghỉ nhiều ngày:\n' + day.day_off_multi_permit + ' phút';
                                        data.time = day.day_off_multi_permit;
                                    } else {
                                        if (day.day_off_full_permit) {
                                            data.class = 'td-full-permit';
                                            data.title = 'Nghỉ cả ngày:\n' + day.day_off_full_permit + ' phút';
                                            data.time = day.day_off_full_permit;
                                        } else {
                                            if (day.day_off_late_without_permit) {
                                                data.class = 'td-late-without-permit';
                                                data.title = 'Đi muộn không phép:\n' + day.day_off_late_without_permit + ' phút';
                                                data.time = day.day_off_late_without_permit;
                                            }
                                            if (day.day_off_late_permit) {
                                                data.class = 'td-late-permit';
                                                data.title = 'Đi muộn có phép:\n' + day.day_off_late_permit + ' phút';
                                                data.time = day.day_off_late_permit;
                                            }
                                            if (day.day_off_late_ot) {
                                                data.class = 'td-late-ot';
                                                data.title = 'Đi muộn do OT:\n' + day.day_off_late_ot + ' phút';
                                                data.time = day.day_off_late_ot;
                                            }
                                            if (day.day_off_leave_early_permit) {
                                                data.class = 'td-leave-early';
                                                data.title = 'Về sớm có phép:\n' + day.day_off_leave_early_permit + ' phút';
                                                data.time = day.day_off_leave_early_permit;
                                            }
                                            if (day.day_off_leave_early_without_permit) {
                                                data.class = 'td-leave-early-without-permit';
                                                data.title = 'Về sớm không phép:\n' + day.day_off_leave_early_without_permit + ' phút';
                                                data.time = day.day_off_leave_early_without_permit;
                                            }
                                            if (day.day_off_without_permit) {
                                                data.class = 'td-without-permit';
                                                data.title = 'Vắng mặt không phép:\n' + day.day_off_without_permit + ' phút';
                                                data.time = day.day_off_without_permit;
                                            }
                                            if (day.day_off_go_out) {
                                                data.class = 'td-go-out';
                                                data.title = 'Ra ngoài:\n' + day.day_off_go_out + ' phút';
                                                data.time = day.day_off_go_out;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        data.time = day.day_off_total ? day.day_off_total : '';
                        this.timeOns[index].time_ons[i].data = data;
                    });
                });
            },
            error => {
                this.loaderController.releaseLoader();
            }

        );
    }
    department: any = null;
    listDepartments = [];
    getListDepartments() {
        this.loaderController.pushLoader();
        this.listDepartments = [];
        const defaultDepartment = new Department();
        defaultDepartment.id = 0;
        defaultDepartment.name = 'Tất cả';
        this.listDepartments.push(defaultDepartment);
        this.department = this.listDepartments[0];
        this.personnelService.getListDeparments().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listDepartments.push(...data.departments);
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    job_status: any = null;

    listJobStatus = [];
    getListJobStatus() {
        this.loaderController.pushLoader();
        const defaultJobStatus = new JobStatus();
        defaultJobStatus.id = 0;
        defaultJobStatus.name = 'Tất cả';
        this.listJobStatus.push(defaultJobStatus);
        this.job_status = this.listJobStatus[0];
        this.personnelService.getListJobStatus().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.listJobStatus.push(...data.jobs_status);

            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );
    }

    checkTimeOnInListDaysInMonth(date) {
        let check = false;
        this.daysInMonth.forEach(element => {
            if (date === element.date) {
                check = true;
            }
        });
        return check;
    }


}
