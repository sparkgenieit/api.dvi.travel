// src/layouts/MainLayout.tsx
import { useState } from "react";
import { Sidebar } from "./Sidebar";
import { Topbar } from "./Topbar";
import { cn } from "@/lib/utils";

interface MainLayoutProps {
  children: React.ReactNode;
}

export const MainLayout = ({ children }: MainLayoutProps) => {
  const [sidebarCollapsed, setSidebarCollapsed] = useState(true);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  // This 'shell' will ONLY be for the Topbar and Footer
  const shell =
    "mx-auto w-full max-w-[1920px] 2xl:max-w-[2048px] px-4 lg:px-6";

  // This 'contentShell' is for your main page content.
  // Notice: NO 'mx-auto' and NO 'max-w-'.
  const contentShell = "w-full px-4 lg:px-6";

  return (
    <div className="min-h-screen w-full bg-background flex">
      <Sidebar
        collapsed={sidebarCollapsed}
        onToggle={() => setSidebarCollapsed(!sidebarCollapsed)}
        mobileOpen={mobileMenuOpen}
        onMobileToggle={() => setMobileMenuOpen(!mobileMenuOpen)}
      />

      <div
        className={cn(
          "flex-1 flex flex-col transition-all duration-300 min-h-screen w-full min-w-0",
          "md:ml-20 lg:ml-20",
          !sidebarCollapsed && "md:ml-64 lg:ml-64"
        )}
      >
        {/* Topbar inside the original centered 'shell' */}
        <div className={shell}>
          <Topbar onMobileMenuToggle={() => setMobileMenuOpen(!mobileMenuOpen)} />
        </div>

        {/* Main content inside the NEW full-width 'contentShell' */}
        <main className="flex-1 overflow-y-auto overflow-x-hidden w-full relative z-10">
          {/* THIS IS THE FIX: 
            We are now using 'contentShell' here instead of 'shell'.
          */}
          <div className={contentShell}>{children}</div>
        </main>

        {/* Footer inside the original centered 'shell' */}
        <footer className="bg-white border-t border-border py-4">
          <div className={shell}>
            <div className="flex items-center justify-center text-sm text-muted-foreground">
              DVI Holidays @ {new Date().getFullYear()}
            </div>
          </div>
        </footer>
      </div>
    </div>
  );
};