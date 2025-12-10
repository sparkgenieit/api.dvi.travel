import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Trash2 } from "lucide-react";

interface DeleteActivityModalProps {
  open: boolean;
  onClose: () => void;
  onConfirm: () => void;
  activityTitle?: string;
}

export const DeleteActivityModal = ({
  open,
  onClose,
  onConfirm,
  activityTitle,
}: DeleteActivityModalProps) => {
  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader className="flex flex-col items-center text-center">
          <div className="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
            <Trash2 className="w-8 h-8 text-red-500" />
          </div>
          <DialogTitle className="text-xl">Are you sure?</DialogTitle>
          <DialogDescription className="text-center">
            Do you really want to delete{" "}
            {activityTitle ? `"${activityTitle}"` : "this record"}? This process
            cannot be undone.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter className="flex gap-2 sm:justify-center">
          <Button variant="outline" onClick={onClose}>
            Close
          </Button>
          <Button variant="destructive" onClick={onConfirm}>
            Delete
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
};
