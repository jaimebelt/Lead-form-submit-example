import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Lead, getLeadsResponse, createLeadResponse } from '../models/lead.interface';
import { environment } from '../../environments/environment';
@Injectable({
  providedIn: 'root'
})
export class LeadService {
  private apiUrl = environment.apiUrl + '/leads';
  private refreshLeadsSubject = new Subject<void>();
  refreshLeads$ = this.refreshLeadsSubject.asObservable();

  constructor(private http: HttpClient) { }

  getLeads(): Observable<getLeadsResponse> {
    return this.http.get<getLeadsResponse>(this.apiUrl);
  }

  createLead(lead: Lead): Observable<createLeadResponse> {
    return this.http.post<createLeadResponse>(this.apiUrl, lead);
  }

  triggerRefreshLeads() {
    this.refreshLeadsSubject.next();
  }
} 