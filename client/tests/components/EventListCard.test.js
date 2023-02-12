import { render, screen } from "@testing-library/vue";
import EventListCard from "src/components/cards/EventListCard.vue";
import events from "../../mock_data/models/events.json";
import { defaultRenderOptions } from "../config";

describe("EventListCard component", () => {
  const event = events[0];

  it("should display the event's name, description and a button link", async () => {
    render(EventListCard, {
      props: { event: event },
      ...defaultRenderOptions,
    });
    expect(screen.getByText(event.name)).toBeInTheDocument();
    expect(screen.getByText(event.description)).toBeInTheDocument();
    expect(screen.getByText("Learn more")).toBeInTheDocument();
  });
});
