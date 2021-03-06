import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/authSevice';
import {Router} from '@angular/router';
import {DataGlobalService} from '../../services/data.global.service';
import {CategoryService} from '../../services/category/category.service';
import { HolidayReponse} from '../../models/api/response/CategoriesReponse';

declare var $: any;
declare var swal: any;


@Component({
    selector: 'department-cmp',
    moduleId: module.id,
    templateUrl: 'holiday.component.html'
})

export class HolidayComponent implements OnInit {
    dataRows: HolidayReponse[] = [];
    isloadSuccess = false;
    form: FormGroup;
    isAdmin = false;
    allowUpdate = false;
    allowAdd = false;

    constructor(private router: Router,
                private serrvice: CategoryService,
                private dataService: DataGlobalService) {

    }


    ngOnInit() {
        if (!this.checkPemision('/danh-muc/phong-ban')) {
            window.history.back();
        } else {
            this.allowUpdate = this.checkPemision('/danh-muc/cap-nhat-phong-ban');
            this.allowAdd = this.checkPemision('/danh-muc/them-phong-ban');
            this.getDataRow();
            this.isAdmin = this.dataService.isAdmin();
            console.log(this.isAdmin);
        }

    }


    checkPemision(path: string): boolean {
        return this.dataService.checkPemisson(path);
    }


    isLoadSuccess(): boolean {
        if (this.isloadSuccess) {
            $('[rel="tooltip"]').tooltip();
        }
        return this.isloadSuccess;
    }

    getDataRow(): void {
        this.serrvice.getList('official_holidays').subscribe(
            repo => {
                console.log(repo);
                this.isloadSuccess = true;
                this.dataRows = (repo.official_holidays as HolidayReponse[]);
            },
            error => {
                this.dataService.actionFail(error.error);
            });
    }

    updateDepartment(data: any): void {
        this.router.navigate(['../danh-muc/cap-nhat-cac-ngay-nghi-le'], {queryParams: data});
    }

    gotoAddHoliday(): void {
        this.router.navigate(['../danh-muc/them-cac-ngay-nghi-le']);
    }

    onBack(): void {
        window.history.back();
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
            },
            allowOutsideClick: false
        }).then(result => {
            this.serrvice.delete('official_holidays', data.id).subscribe(
                repo => {
                    if (repo.status === 'success') {
                        swal('Đã Xóa!', 'Bạn đã xóa dịp nghỉ lễ thành công', 'success');
                        this.dataRows = this.dataRows.filter(h => h !== data);
                    }
                },
                error => {
                    this.dataService.actionFail(error.error);
                });

        });
    }


}