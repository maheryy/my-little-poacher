import { render, screen } from "@testing-library/vue";
import TicketList from "src/views/protected/TicketList.vue";
import tickets from "mock_data/models/tickets.json";
import { defaultRenderOptions } from "../../config";

describe("the ticket list page", () => {
  it("should display the main ticket list title", async () => {
    render(TicketList, { ...defaultRenderOptions });
    expect(screen.getByText("My tickets")).toBeInTheDocument();
  });

  it("should display multiple ticket cards", async () => {
    render(TicketList, { ...defaultRenderOptions });
    expect(await screen.findAllByRole("listitem")).toHaveLength(tickets.length);
  });
});
