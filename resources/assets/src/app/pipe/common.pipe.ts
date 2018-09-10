import { Pipe, PipeTransform } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import * as moment from 'moment';

@Pipe({ name: 'dateFormatVn' })
export class DateFormatVNPipe implements PipeTransform {
  constructor() { }
  transform(date) {
    if (date && date !== null && date !== '') {
        return moment(date, 'YYYY-MM-DD').format('DD-MM-YYYY');
    }
  }
}

@Pipe({ name: 'dayOfWeek' })
export class DayOfWeekPipe implements PipeTransform {
  constructor() { }
  transform(date) {
    if (date && date !== null && date !== '') {
        return moment(date, 'YYYY-MM-DD').day();
    }
  }
}

@Pipe({ name: 'hourFormatWithoutSecond' })
export class HourFormatWithoutSecondPipe implements PipeTransform {
  constructor() { }
  transform(hour) {
    if (hour && hour !== null && hour !== '') {
        return moment(hour, 'HH:mm:ss').format('HH:mm');
    }
  }
}

@Pipe({ name: 'timeoffStatus' })
export class TimeOffStatusPipe implements PipeTransform {
  constructor() { }
  transform(status) {
    let text = '';
    if (status && status !== null && status !== '') {
        switch (status) {
          case 1: text =  'Đi muộn'; break;
          case 2: text = 'Về sớm'; break;
          case 3: text = 'Ra ngoài'; break;
          case 4: text = 'Quên checkin checkout'; break;
          case 5: text = 'Nghỉ cả ngày'; break;
          case 6: text = 'Nghỉ nhiều ngày'; break;
          case 7: text = 'OT'; break;
        }
    }
    return text;
  }
}
