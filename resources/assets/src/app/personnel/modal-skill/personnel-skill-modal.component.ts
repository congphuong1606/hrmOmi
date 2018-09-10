import {
    Component,
    OnInit,
    AfterViewInit,
    ElementRef,
    ViewChild,
    Input,
    Output,
    OnChanges,
    SimpleChanges,
    Renderer2,
    EventEmitter
} from '@angular/core';
import {FormBuilder, FormGroup, FormControl, Validators} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/authSevice';
import {Employee, Pagination, JobStatus, WorkingStatus} from '../../models/api/response/ListEmployeesResponse';
import {SearchEmployeeFormRequest, Department, Position} from '../../models/api/request/ListEmployeesRequest';
import {PersonnelService} from '../../services/personnel.service';
import {Router} from '@angular/router';
import {FileValidator} from '../../validation/file-validator';
import {LoaderController} from '../../shared/loader/loader';
import {DataGlobalService} from '../../services/data.global.service';

declare var swal: any;
declare var $: any;

@Component({
    selector: 'personnel-skill-modal-cmp',
    moduleId: module.id,
    templateUrl: 'personnel-skill-modal.component.html',
    styles: [`
        .modal-body {
            padding: 0 !important;
        }

        .ngx-datatable.material {
            box-shadow: none;
        }
    `]
})


export class PersonnelSkillModelComponent implements OnInit, OnChanges {

    rows = [];
    selected = [];
    selected2 = [];
    selectedRows = [];
    loaderController: LoaderController = new LoaderController();
    @ViewChild('modal') modal: ElementRef;
    @Input('show') show: boolean;
    @Input('currentSelected') currentSelected: any;
    @Output() close: EventEmitter<any> = new EventEmitter<any>();
    @Input('initSkills') initSkills: any = [];
    hasPermissionEditSkills = false;

    constructor(private elementRef: ElementRef, private rd: Renderer2, private http: HttpClient,
                private personnelService: PersonnelService,
                private dataGlobalService: DataGlobalService,
                private router: Router,) {

    }

    ngOnInit() {
        this.loaderController.pushLoader();
        if (this.dataGlobalService.checkPemisson('/danh-muc/danh-sach-trong-danh-muc-khac/ky-nang-chuyen-mon')) {
            this.hasPermissionEditSkills = true;
        }
        this.personnelService.getListSkills().subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.rows = [];
                data.specialized_skills.forEach((value, index) => {
                    this.rows.push({
                        id: value.id,
                        name: value.name,
                        description: value.description
                    });
                });
                const sel = [];
                this.currentSelected.forEach(element => {
                    this.rows.forEach((value) => {
                        if (element.id === value.id) {
                            sel.push(value);

                        }
                    });
                });
                this.selected.splice(0, this.selected.length);
                this.selected.push(...sel);
            },
            error => {
                this.loaderController.releaseLoader();
                console.log(error);
            }
        );

    }

    gotoEditSkills() {
        const item = {
            name: 'Kỹ năng chuyên môn',
            path: 'ky-nang-chuyen-mon',
            type: 'specialized_skills',
        };

        this.router.navigate(['/danh-muc/danh-sach-trong-danh-muc-khac/', item.path], {
            queryParams: {
                category_type: item.type,
                category_name: item.name,
            }
        });
    }

    ngOnChanges(changes: SimpleChanges) {
        if (changes.show && changes.show.currentValue) {
            $(this.modal.nativeElement).modal('show');
        } else {
            $(this.modal.nativeElement).modal('hide');
        }
    }

    closeModal() {
        this.close.emit(this.selected);
    }

    fetch(cb) {
        const req = new XMLHttpRequest();
        req.open('GET', `assets/data/company.json`);

        req.onload = () => {
            cb(JSON.parse(req.response));
        };

        req.send();
    }

    onSelect({selected}) {
        console.log('Select Event', selected, this.selected);
        this.selected.splice(0, this.selected.length);
        this.selected.push(...selected);
    }

    onActivate(event) {
    }

    add() {
        this.selected.push(this.rows[1], this.rows[3]);
    }

    update() {
        this.selected = [this.rows[1], this.rows[3]];
    }

    remove() {
        this.selected = [];
    }

    displayCheck(row) {
        return row.name !== 'Ethel Price';
    }

    getRowHeight(row) {
        if (!row) return 50;
        if (row.height === undefined) return 50;
        return row.height;
    }
}
