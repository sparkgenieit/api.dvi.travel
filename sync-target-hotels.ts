import { NestFactory } from '@nestjs/core';
import { AppModule } from './src/app.module';
import { TboSoapSyncService } from './src/modules/hotels/services/tbo-soap-sync.service';

async function syncHotels() {
  const app = await NestFactory.create(AppModule);
  const syncService = app.get(TboSoapSyncService);

  const cityCodes = ['126117', '139605', '127067', '133179'];
  const cityNames = ['Mahabalipuram', 'Thanjavur', 'Madurai', 'Rameswaram'];

  console.log('\n🚀 STARTING HOTEL SYNC FOR TARGET CITIES\n');
  console.log('════════════════════════════════════════════\n');

  for (let i = 0; i < cityCodes.length; i++) {
    const cityCode = cityCodes[i];
    const cityName = cityNames[i];

    try {
      console.log(`\n🏨 Syncing hotels for: ${cityName} (City Code: ${cityCode})`);
      console.log('─────────────────────────────────────────');
      
      const count = await syncService.syncHotelsForCity(cityCode);
      
      console.log(`✅ SUCCESS: Synced ${count} hotels for ${cityName}`);
    } catch (error) {
      const msg = error instanceof Error ? error.message : String(error);
      console.error(`❌ FAILED: ${msg}`);
    }
  }

  console.log('\n════════════════════════════════════════════');
  console.log('🎉 SYNC COMPLETED\n');

  await app.close();
  process.exit(0);
}

syncHotels().catch(err => {
  console.error('Fatal error:', err);
  process.exit(1);
});
