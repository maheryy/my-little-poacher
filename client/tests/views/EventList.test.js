import { render, screen } from "@testing-library/vue";
import EventList from "src/views/EventList.vue";
import events from "../../mock_data/models/events.json";
import { defaultRenderOptions } from "../config";

describe("The event list page", () => {
  it("should display the main title", async () => {
    render(EventList, { ...defaultRenderOptions });
    expect(screen.getByText("Upcoming events")).toBeInTheDocument();
  });

  it("should display multiple events card", async () => {
    render(EventList, { ...defaultRenderOptions });
    expect(await screen.findAllByText("Learn more")).toHaveLength(
      events.length
    );
  });
});
