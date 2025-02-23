import { Component, OnInit } from '@angular/core';
import { LeadService } from '../../services/lead.service';
import { Lead } from '../../models/lead.interface';

@Component({
  selector: 'app-lead-list',
  templateUrl: './lead-list.component.html',
  styleUrls: ['./lead-list.component.scss']
})
export class LeadListComponent implements OnInit {
  leads: Lead[] = [];
  displayedColumns: string[] = ['lead_id', 'name', 'email', 'source'];

  constructor(private leadService: LeadService) { }

  ngOnInit(): void {
    this.loadLeads();
  }

  loadLeads(): void {
    this.leadService.getLeads().subscribe({
      next: (leads) => {
        this.leads = leads;
      },
      error: (error) => {
        console.error('Error loading leads:', error);
        // You might want to add error handling here
      }
    });
  }
} 