import { render, screen, waitFor } from "@testing-library/vue";
import Event from "src/views/Event.vue";
import events from "../../mock_data/models/events.json";
import { defaultRenderOptions } from "../config";
import userEvent from "@testing-library/user-event";
import jest from "jest-mock";

window.confirm = jest.fn(() => true);

describe("Event page", () => {
  const event = events[0];

  it("should display the main event title", async () => {
    render(Event, {
      props: { id: `${event.id}` },
      ...defaultRenderOptions,
    });
    expect(await screen.findByText(event.name)).toBeInTheDocument();
  });

  it("should display the event description, price and organizer", async () => {
    render(Event, { ...defaultRenderOptions });
    expect(
      await screen.findByText(`Organized by ${event.creator.name}`)
    ).toBeInTheDocument();
    expect(await screen.findByText(event.description)).toBeInTheDocument();
    expect(
      await screen.findByText(`Price : ${event.price} €`)
    ).toBeInTheDocument();
  });

  it("should display the cta 'Book this event'", async () => {
    render(Event, { ...defaultRenderOptions });
    expect(await screen.findByText("Book this event")).toBeInTheDocument();
  });

  it("should display confirmation dialog when clicking on the cta", async () => {
    render(Event, { ...defaultRenderOptions });
    await userEvent.click(screen.getByText("Book this event"));

    expect(window.confirm).toHaveBeenCalled();

    window.confirm.mockClear();
  });
});
