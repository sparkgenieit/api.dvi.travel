// FILE: src/pages/activity/ActivityPreviewPage.tsx

import { useQuery } from "@tanstack/react-query";
import { ActivitiesAPI } from "@/services/activities";
import type { PreviewPayload } from "@/services/activities"; // <-- if you need the type
import { useParams, useNavigate } from "react-router-dom";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import BasicPreview from "./PreviewPieces"; // default export only — no named PreviewPayload

export default function ActivityPreviewPage() {
  const { id } = useParams();
  const navigate = useNavigate();

  const activityId = Number(id);
  const { data, isLoading, error } = useQuery<PreviewPayload>({
    queryKey: ["activities", "preview", activityId],
    queryFn: () => ActivitiesAPI.preview(activityId),
    enabled: Number.isFinite(activityId),
  });

  if (!Number.isFinite(activityId)) {
    return <div className="p-6">Invalid activity id.</div>;
  }
  if (isLoading) return <div className="p-6">Loading…</div>;
  if (error) return <div className="p-6 text-red-600">Failed to load preview.</div>;
  if (!data) return <div className="p-6">No data.</div>;

  return (
    <div className="p-6">
      <h1 className="text-xl font-semibold text-[#6b7280] mb-4">Activity Preview</h1>
      <Card>
        <CardContent className="p-6">
          {/* Pass the payload exactly as the component expects */}
          <BasicPreview data={data} />
          <div className="mt-6 flex justify-end">
            <Button variant="outline" onClick={() => navigate("/activities")}>
              Back
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
