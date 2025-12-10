// FILE: src/pages/vendor/vendorFormTypes.ts

export type Option = { id: string; label: string };

export type BasicInfoForm = {
  vendorName: string;
  email: string;
  primaryMobile: string;
  altMobile: string;
  otherNumber: string;
  countryId: string;
  stateId: string;
  cityId: string;
  pincode: string;
  username: string;
  password: string;
  roleId: string;
  marginPercent: string;
  marginGstType: string;
  marginGstPercent: string;
  address: string;
  invoiceCompanyName: string;
  invoiceAddress: string;
  invoicePincode: string;
  invoiceGstin: string;
  invoicePan: string;
  invoiceContactNo: string;
  invoiceEmail: string;
};

export type BranchForm = {
  id?: number;
  name: string;
  location: string;
  email: string;
  primaryMobile: string;
  altMobile: string;
  countryId: string;
  stateId: string;
  cityId: string;
  pincode: string;
  gstType: string;
  gstPercent: string;
  address: string;
};
