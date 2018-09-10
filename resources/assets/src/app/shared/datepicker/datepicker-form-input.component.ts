import { Component, OnInit, AfterViewInit, Renderer, ElementRef, ViewChild, forwardRef, Input } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators, ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';
import { log } from 'util';
import { HttpClient } from '@angular/common/http';
import { AuthService } from '../../services/authSevice';
import { Employee, Pagination, JobStatus, WorkingStatus } from '../../models/api/response/ListEmployeesResponse';
import { SearchEmployeeFormRequest, Department, Position } from '../../models/api/request/ListEmployeesRequest';
import { PersonnelService } from '../../services/personnel.service';
import { Router } from '@angular/router';
import { FileValidator } from '../../validation/file-validator';
import { DataGlobalService } from '../../services/data.global.service';
declare var swal: any;
declare var $: any;

@Component({
    selector: 'datepicker-form-input-cmp',
    moduleId: module.id,
    templateUrl: 'datepicker-form-input.component.html',
    providers: [
        {
            provide: NG_VALUE_ACCESSOR,
            useExisting: forwardRef(() => DapickerFormInputComponent),
            multi: true
        }
    ]
})

export class DapickerFormInputComponent implements OnInit, AfterViewInit, ControlValueAccessor {
    @ViewChild('picker') picker: ElementRef;
    @Input('format') format: string;
    onChange = (date: any) => { };
    ngAfterViewInit(): void {

    }
    writeValue(obj: any): void {
        $(this.picker.nativeElement).val(obj);
    }
    registerOnChange(fn: any): void {
        this.onChange = fn;
    }
    registerOnTouched(fn: any): void {

    }
    setDisabledState?(isDisabled: boolean): void {

    }

    constructor(
        private renderer: Renderer,
        private element: ElementRef) {
    }

    ngOnInit() {
        let fm = '';
        if (!this.format || this.format === '') {
            fm = 'DD-MM-YYYY';
        } else {
            fm = this.format;
        }
        $(this.picker.nativeElement).datetimepicker({
            format: fm,     //use this format if you want the 12hours timpiecker with AM/PM toggle
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
            },
            ignoreReadonly: true,
        });
        $(this.picker.nativeElement).on('dp.change', (e) => {
            this.onChange(e.date.format(fm));
        });
    }

    get value() {
        return $(this.picker.nativeElement).val();
    }
}