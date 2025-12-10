export interface Hotspot {
  id: string;
  name: string;
  type: string;
  priority: number;
  description: string;
  landmark: string;
  address: string;
  
  // Entry costs
  adultCost: number;
  childCost: number;
  infantCost: number;
  foreignAdultCost: number;
  foreignChildCost: number;
  foreignInfantCost: number;
  
  // Location
  rating: number;
  duration: string;
  latitude: string;
  longitude: string;
  videoUrl: string;
  locations: string[];
  
  // Media
  galleryImages: string[];
  
  // Parking charges
  parkingCharges: {
    sedan: number;
    innova: number;
    innovaCrysta6: number;
    tempoTraveller12: number;
    muv6: number;
    innova7: number;
    innovaCrysta7: number;
    benz26: number;
    leyland36: number;
    leyland40: number;
    benzLarge45: number;
    volvo43: number;
  };
  
  // Opening hours
  openingHours: {
    [key: string]: {
      is24Hours: boolean;
      timeSlots: string[];
    };
  };
}
