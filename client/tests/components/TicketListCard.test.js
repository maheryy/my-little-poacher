import { render, screen } from "@testing-library/vue";
import TicketListCard from "src/components/cards/TicketListCard.vue";
import tickets from "../../mock_data/models/tickets.json";
import { defaultRenderOptions } from "../config";

describe("TicketListCard component", () => {
  const ticket = tickets[0];

  it("should display the ticket's reference and status", () => {
    render(TicketListCard, {
      props: { ticket: ticket },
      ...defaultRenderOptions,
    });
    expect(screen.getByText(ticket.reference)).toBeInTheDocument();
    expect(screen.getByText(ticket.status)).toBeInTheDocument();
  });

  it("should display the related event's name and capacity", () => {
    render(TicketListCard, {
      props: { ticket: ticket },
      ...defaultRenderOptions,
    });
    expect(screen.getByText(ticket.event.name)).toBeInTheDocument();
    expect(
      screen.getByText(`Up to ${ticket.event.capacity} attendees`)
    ).toBeInTheDocument();
  });

  it("should have a CTA button when the ticket status is still 'pending'", () => {
    const ticket = tickets[2]; // pending ticket

    render(TicketListCard, {
      props: { ticket: ticket },
      ...defaultRenderOptions,
    });

    expect(screen.getByText(ticket.status)).toHaveTextContent("pending");
    expect(screen.getByText(`Pay ${ticket.event.price} €`)).toBeInTheDocument();
  });

  it("should not display a CTA button when a ticket has been paid", () => {
    const ticket = tickets[1]; // paid ticket

    render(TicketListCard, {
      props: { ticket: ticket },
      ...defaultRenderOptions,
    });

    expect(screen.queryByRole("button")).not.toBeInTheDocument();
  });
});
