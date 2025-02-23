export interface Lead {
  lead_id?: string;
  name: string;
  email: string;
  source: string;
}

export interface getLeadsResponse {
  data: Lead[];
  message: string;
  success: boolean;
}

export interface createLeadResponse {
  data: Lead;
  message: string;
  success: boolean;
}
