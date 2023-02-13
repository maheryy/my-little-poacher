import { render, screen } from "@testing-library/vue";
import Ticket from "src/views/protected/Ticket.vue";
import tickets from "mock_data/models/tickets.json";
import { defaultRenderOptions } from "../../config";

describe("the ticket page", () => {
  const ticket = tickets[0];
  it("should display the ticket reference as title", async () => {
    render(Ticket, {
      props: { ticket: ticket },
      ...defaultRenderOptions,
    });
    expect(
      await screen.findByText(`Ticket n°${ticket.reference}`)
    ).toBeInTheDocument();
  });

  it("should display a Qrcode", async () => {
    render(Ticket, {
      props: { ticket: ticket },
      ...defaultRenderOptions,
    });
    expect(await screen.findByRole("img")).toBeInTheDocument();
  });
});
